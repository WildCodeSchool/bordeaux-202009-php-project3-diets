<?php

namespace App\Service\Stripe;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session as Session;
use Stripe\Customer as Customer;
use Stripe\StripeClient as StripeClient;
use Stripe\BillingPortal\Session as SessionStripe;
use Stripe\BillingPortal\Configuration as Configuration;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Stripe\Subscription as Subscription;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StripeSubscribeService
{

    protected $session;
    protected $params;
    protected $userRepository;
    protected $entityManager;

    public function __construct(SessionInterface $session, ParameterBagInterface $params, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->params = $params;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->entityManager = $entityManager;
    }

    public function createPortalSession()
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $configuration = Configuration::create([
            'business_profile' => [
                'privacy_policy_url' => 'https://example.com/privacy',
                'terms_of_service_url' => 'https://example.com/terms',
            ],
            'features' => [
                'invoice_history' => ['enabled' => true],
                "subscription_cancel" => [
                "enabled" => true,
                "mode" => "at_period_end",
                "proration_behavior" => "none"]
            ]
        ]);

        Stripe::setApiKey($this->params->get('api_key'));

        $customers = Customer::all();
        foreach ($customers as $customer) {
            if ($customer['email'] === $this->session->get('userEmail')) {
                $customerId = $customer['id'];
            }
        }

        $sessionStripe = SessionStripe::create([
            'customer' => $customerId,
            'configuration' => $configuration['id'],
            'return_url' => $this->params->get('return_url'),
        ]);



        return $sessionStripe;
    }

    public function createSubscription()
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $customers = Customer::all();
        foreach ($customers as $customer) {
            if ($customer['email'] === $this->session->get('userEmail')) {
                $customerId = $customer['id'];
            }
        }

        $stripe = new StripeClient($this->params->get('api_key'));

        if (!isset($customerId)) {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $this->params->get('company_price'),
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->params->get('success_url_subscription'),
                'cancel_url' => $this->params->get('error_url'),
            ]);
        } else {
            $session = Session::create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $this->params->get('company_price'),
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->params->get('success_url_subscription'),
                'cancel_url' => $this->params->get('error_url'),
            ]);
        }

        return $session;
    }

    public function changeStatusForSubscriber()
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $customerId = '';
        $customers = Customer::all();

        foreach ($customers as $customer) {
            if ($customer['email'] === $this->session->get('userEmail')) {
                $customerId = $customer['id'];
            }
        }

        $subscriptions = Subscription::all(['limit' => 100]);

        $result = null;


        foreach ($subscriptions as $subscription) {
            if ($subscription['customer'] === $customerId) {
                $result = $customerId;
                $takeuser = $this->user;
                if ($takeuser->getRoles(['ROLE_COMPANY'])) {
                    $changeRole = $takeuser->setRoles(['ROLE_COMPANY_SUBSCRIBER']);
                }
                if ($takeuser->getRoles(['ROLE_FREELANCER'])) {
                    $changeRole = $takeuser->setRoles(['ROLE_FREELANCER_SUBSCRIBER']);
                }
                $this->entityManager->persist($changeRole);
                $this->entityManager->flush();
            }
        }

        if (is_null($result)) {
            $takeuser = $this->user;
            if (in_array('ROLE_COMPANY_SUBSCRIBER', $takeuser->getRoles(), $strict = true)) {
                $changeRole = $takeuser->setRoles(['ROLE_COMPANY']);
                $this->entityManager->persist($changeRole);
                $this->entityManager->flush();
            }
            if (in_array('ROLE_FREELANCER_SUBSCRIBER', $takeuser->getRoles(), $strict = true)) {
                $changeRole = $takeuser->setRoles(['ROLE_FREELANCER']);
                $this->entityManager->persist($changeRole);
                $this->entityManager->flush();
            }
        }

        return $subscription['status'];
    }
}
