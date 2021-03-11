<?php


namespace App\Service\Stripe;


use Stripe\Stripe as Stripe;
use Stripe\Event as Event;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WebhookStripeService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }


    public function webhookEndpoint()
    {
        Stripe::setApiKey($this->params->get('api_key'));

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        $paymentIntent = '';

// Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            /*case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                break;*/
            default:
                // Unexpected event type
                echo 'Received unknown event type';
        }

        http_response_code(200);

        return $paymentIntent;

    }

}
