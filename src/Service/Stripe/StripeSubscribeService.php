<?php


namespace App\Service\Stripe;


use Stripe\Stripe;
use Stripe\Checkout\Session as Session;
use Stripe\Customer as Customer;
use Stripe\StripeClient as StripeClient;
use Stripe\BillingPortal\Session as SessionStripe;
use Stripe\BillingPortal\Configuration as Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeSubscribeService extends AbstractController
{

    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function createPortalSession()
    {
        Stripe::setApiKey($this->getParameter('api_key'));

        $configuration = Configuration::create([
            'business_profile' => [
                'privacy_policy_url' => 'https://example.com/privacy',
                'terms_of_service_url' => 'https://example.com/terms',
            ],
            'features' => [
                'invoice_history' => ['enabled' => true],
            ],
        ]);

        Stripe::setApiKey($this->getParameter('api_key'));

        $customers = Customer::all();
        foreach ($customers as $customer) {
            if ($customer['email'] === $this->session->get('userEmail')) {
                $customerId = $customer['id'];
            }
        }
        $sessionStripe = SessionStripe::create([
            'customer' => $customerId,
            'configuration' => $configuration['id'],
            'return_url' => 'https://nouslesdiets.fr/account',
        ]);

        return $sessionStripe;
    }

    public function createSubscription()
    {
        Stripe::setApiKey($this->getParameter('api_key'));

        $customers = \Stripe\Customer::all();
        foreach ($customers as $customer) {
            if ($customer['email'] === $this->session->get('userEmail')) {
                $customerId = $customer['id'];
            }
        }

        $stripe = new StripeClient($this->getParameter('api_key'));

        if (!isset($customerId)) {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $this->getParameter('company_price'),
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
        } else {
            $session = Session::create([
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $this->getParameter('company_price'),
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
        }
        return $session;
    }
}
