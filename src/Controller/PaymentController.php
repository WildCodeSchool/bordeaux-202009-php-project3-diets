<?php

namespace App\Controller;

use App\Service\Basket\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Stripe as Stripe;
use Stripe\Checkout\Session as CheckoutSession;

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
     * @Route("/creation-session", name="checkout")
     */
    public function checkout(BasketService $basketService): Response
    {
        Stripe::setApiKey($this->getParameter('api_key'));

        $session = CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => $basketService->getTotal()*100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('payment_error', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new JsonResponse(['id'=> $session ->id]);

    }


}
