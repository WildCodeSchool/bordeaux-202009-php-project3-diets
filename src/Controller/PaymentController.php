<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Stripe\StripeService;
use Stripe\Account as Account;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use \Stripe\Stripe as Stripe;
use Symfony\Component\Routing\Annotation\Route;
use \Stripe\Checkout\Session as CheckoutSession;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;


/**
 * @Route("/paiement", name="payment_")
 */
class PaymentController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @Route("/valider", name="success")
     */

    public function success(): Response
    {
        return $this->render('basket/success.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @Route("/erreur", name="error")
     */

    public function error(): Response
    {
        return $this->render('basket/error.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @Route("/creation-session/", name="checkout")
     */
    public function checkout(StripeService $stripeService): Response
    {
        $session = $stripeService->checkoutCreateSession();


        return new JsonResponse(['id' => $session ->id]);

    }

    /**
     * @Route ("/inscription-stripe/{id}", name="register_stripe")
     */

    public function registerStripe(StripeService $stripeService, int $id)
    {
        $stripe = $stripeService->createLinkAccount($id);

        return $this->redirect($stripe);

    }


    /**
     * @Route ("/creation-portail-session", name="create_portal_session")
     */
    public function createStripePortalSession(StripeService $stripeService, SessionInterface $session)
    {

        \Stripe\Stripe::setApiKey(
            $this->getParameter('api_key')
        );

        $configuration = \Stripe\BillingPortal\Configuration::create([
            'business_profile' => [
                'privacy_policy_url' => 'https://example.com/privacy',
                'terms_of_service_url' => 'https://example.com/terms',
            ],
            'features' => [
                'invoice_history' => ['enabled' => true],
            ],
        ]);

        \Stripe\Stripe::setApiKey($this->getParameter('api_key'));

// Authenticate your user.
        Stripe::setApiKey($this->getParameter('api_key'));

        $customers = \Stripe\Customer::all();
        foreach ($customers as $customer){
            if ($customer['email'] === $session->get('userEmail')) {
                $customerId = $customer['id'];
            }
        }
        dump($customerId);

        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $customerId,
            'configuration' => $configuration['id'],
            'return_url' => 'https://nouslesdiets.fr/account',
        ]);

// Redirect to the customer portal.
        header("Location: " . $session->url);
        exit();
    }

    /**
     * @Route ("/create-subscription/", name="create_subscription")
     */
    public function createStripeSubscription(StripeService $stripeService,
                                             UserRepository $userRepository,
                                             SessionInterface $session): Response
    {

        Stripe::setApiKey($this->getParameter('api_key'));

        $customers = \Stripe\Customer::all();
        foreach ($customers as $customer) {
            if ($customer['email'] === $session->get('userEmail')) {
                $customerId = $customer['id'];
            }
        }

        $stripe = new \Stripe\StripeClient($this->getParameter('api_key'));

        if(!isset($customerId)){
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $this->getParameter('basic_price'),
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);

            return new JsonResponse(['id' => $session ->id]);

        } else {
        $session = \Stripe\Checkout\Session::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $this->getParameter('basic_price'),
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new JsonResponse(['id' => $session ->id]);
        }
    }


}
