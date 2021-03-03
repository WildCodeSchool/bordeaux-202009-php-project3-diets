<?php

namespace App\Controller;


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


        return new JsonResponse(['id'=> $session ->id]);

    }

    /**
     * @Route ("/inscription-stripe/{id}", name="register_stripe")
     */

    public function registerStripe(StripeService $stripeService, int $id)
    {
        $stripe = $stripeService->createLinkAccount($id);

        return $this->redirect($stripe);

    }





}
