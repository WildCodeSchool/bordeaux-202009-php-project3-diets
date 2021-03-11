<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Stripe\StripeService;
use App\Service\Stripe\StripeSubscribeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ServerBag;
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

    public function success(SessionInterface $session, StripeService $stripeService, Request $request): Response
    {
        $session->set('basket', []);

       /* \Stripe\Stripe::setApiKey('sk_test_51ILlKBLs9HAnEomvW4fCbd6XOcmVOO3VNbFNxOPf91KJbm53lKu7ry3RT2aB5gwWtMuOcgPHdBwmDGHqeFO8Hfdj003Q9JwUrd');

        function print_log($val) {
            return file_put_contents('php://stderr', print_r($val, TRUE));
        }

        $endpoint_secret = 'whsec_dZSYn8K8Rjpq8XuSMCx1nt9mQQe4Wmyk';

        $payload = @file_get_contents('php://input');


        $sig_header = $request->server->get('HTTP_STRIPE_SIGNATURE');
        $event = null;


        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }
        function fulfill_order($session) {
            print_log("Fulfilling order...");
            print_log($session);
        }

// Handle the checkout.session.completed event
       if ($event->type == 'checkout.session.completed') {
            $session = $event->data->object;

            // Fulfill the purchase...
            fulfill_order($session);
        }

        return new Response(200);*/


        return $this->render('basket/success.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @Route("/abonnement/valider/", name="subscription_success")
     */

    public function subscriptionSuccess(StripeSubscribeService $stripeSubscribeService): Response
    {

        $statusSubscriber = $stripeSubscribeService->changeStatusForSubscriber();



        return $this->render('payment/subscription_success.html.twig', [
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
    public function createStripePortalSession(StripeSubscribeService $stripeSubscribeService)
    {
        $createPortal = $stripeSubscribeService->createPortalSession();

        header("Location: " . $createPortal->url);
        exit();
    }

    /**
     * @Route ("/create-subscription/", name="create_subscription")
     */
    public function createStripeSubscription(StripeSubscribeService $stripeSubscribeService): Response
    {
        $createSubscription = $stripeSubscribeService->createSubscription();

        return new JsonResponse(['id' => $createSubscription ->id]);
    }
}
