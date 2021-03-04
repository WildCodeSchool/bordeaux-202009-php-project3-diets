<?php

namespace App\Controller;


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
     * @Route ("/creation-client/{id}", name="create_customer")
     */
    public function createStripeCustomer(StripeService $stripeService, int $id, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);
        $email = $user->getEmail();

        Stripe::setApiKey($this->getParameter('api_key'));



        $stripe = new \Stripe\StripeClient(
            $this->getParameter('api_key')
        );
        $stripe->customers->create([
            'email' => $user->getEmail(),
        ]);


        return $this->redirectToRoute('ressource_index');
    }

    /**
     * @Route ("/creation-portail-session", name="create_portal_session")
     */
    public function createStripePortalSession(StripeService $stripeService)
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

        dump($configuration);


        \Stripe\Stripe::setApiKey($this->getParameter('api_key'));

// Authenticate your user.
        $session = \Stripe\BillingPortal\Session::create([
            'customer' => 'cus_J3JWumEl1K5WhV',
            'configuration' => $configuration['id'],
            'return_url' => 'https://example.com/account',
        ]);

// Redirect to the customer portal.
        header("Location: " . $session->url);
        exit();
    }

    /**
     * @Route ("/subscription", name="subscription")
     */
    public function stripeSubscription(StripeService $stripeService)
    {

        $stripe = new \Stripe\StripeClient(
            $this->getParameter('api_key')
        );
        $stripe->subscriptionItems->create([
            'subscription' => 'sub_J3346OSj4tnUll',
            'price' => 'price_1IQwNGEL1K4XLo1p8PbZ8mR4',
            'quantity' => 1,
        ]);
    }
}
