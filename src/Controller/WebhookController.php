<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe as Stripe;

class WebhookController extends AbstractController
{
    /**
     * @Route("/webhook", name="webhook")
     */
    public function index(): Response
    {

        Stripe::setApiKey($this->getParameter('api_key'));

        $payload = @file_get_contents('php://input');
        $event = null;



        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

// Handle the event
        $paymentIntent = '';
        $paymentMethod = '';

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;
            default:
                // Unexpected event type
                echo 'Received unknown event type';
        }


        http_response_code(200);
        return $this->render('webhook/index.html.twig', [
            'payment_intent' => $paymentIntent,
            'payment_method' => $paymentMethod,
        ]);
    }
}
