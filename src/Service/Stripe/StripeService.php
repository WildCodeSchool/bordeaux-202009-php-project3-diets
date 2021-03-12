<?php

namespace App\Service\Stripe;

use App\Entity\SecuringPurchases;
use App\Repository\ResourceRepository;
use App\Repository\SecuringPurchasesRepository;
use App\Repository\UserRepository;
use App\Service\Basket\BasketService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe as Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Account as Account;
use Stripe\AccountLink as AccountLink;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StripeService
{
    protected $userRepository;
    protected $basketService;
    protected $session;
    protected $resourceRepository;
    protected $params;
    protected $securingPurchasesRepository;
    protected $entityManager;
    protected $user;

    public function __construct(
        UserRepository $userRepository,
        BasketService $basketService,
        SessionInterface $session,
        ResourceRepository $resourceRepository,
        ParameterBagInterface $params,
        SecuringPurchasesRepository $securingPurchasesRepository,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->userRepository = $userRepository;
        $this->basketService = $basketService;
        $this->session = $session;
        $this->resourceRepository = $resourceRepository;
        $this->params = $params;
        $this->securingPurchasesRepository = $securingPurchasesRepository;
        $this->entityManager = $entityManager;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function createAccount(int $id): void
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $account = Account::create([
            'type' => 'standard',
            'email' => $this->userRepository->find($id)->getEmail(),
        ]);
    }

    public function createLinkAccount(int $id)
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $accounts = Account::all();

        foreach ($accounts as $account) {
            if ($account['email'] === $this->userRepository->find($id)->getEmail()) {
                $accountId = $account['id'];
            }
        }

        $account_links = AccountLink::create([
            'account' => $accountId,
            'refresh_url' => $this->params->get('refresh_url'),
            'return_url' => $this->params->get('return_url'),
            'type' => 'account_onboarding',
        ]);

        $url = $account_links->url;
        return $url;
    }

    public function checkoutCreateSession()
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $date = new \DateTime('now');

        $basket = $this->session->get('basket');

        $shopping = '';

        foreach ($basket as $id => $shop) {
            $shopping = $this->resourceRepository->find($id)->getName();
            $shoppingId = $this->resourceRepository->find($id)->getId();
        }

        $accountId = $this->getAccountId();


        $token = uniqid();
        $newSecuringPurchase = new SecuringPurchases();
        $newSecuringPurchase->setIdentifier($token);
        $newSecuringPurchase->setUser($this->user);
        $this->entityManager->persist($newSecuringPurchase);
        $this->entityManager->flush();


        Stripe::setApiKey($this->params->get('api_key'));

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Achat du ' . $date->format('Y-m-d H:i:s'),
                        'description' => $shopping,
                    ],
                    'unit_amount' => $this->basketService->getTotal() * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'payment_intent_data' => [
                'application_fee_amount' => round(($this->basketService->getTotal() * 100) * 0.1),
                'setup_future_usage' => 'off_session',
            ],
            'success_url' => $this->params->get('success_url') . '?token=' . $token . '&achat=' . $shoppingId ,
            'cancel_url' => $this->params->get('error_url'),
         ], ['stripe_account' => $accountId]);

        return $session;
    }

    public function getAccountId()
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $accounts = Account::all(['limit' => 100]);

        $basket = $this->session->get('basket');

        $id = '';

        if (isset($basket)) {
            foreach ($basket as $id => $shop) {
                $shopping = $this->resourceRepository->find($id)->getName();
            }
        }


        foreach ($accounts as $account) {
            if (empty($id)) {
                $accountId = '';
            } else {
                if ($account['email'] === $this->resourceRepository->find($id)->getUser()->getEmail()) {
                    $accountId = $account['id'];
                }
            }
        }
        return $accountId;
    }
}
