<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\SecuringPurchases;
use App\Entity\Shopping;
use App\Entity\User;
use App\Repository\ResourceRepository;
use App\Repository\SecuringPurchasesRepository;
use App\Repository\UserRepository;
use App\Service\Basket\BasketService;
use App\Service\Stripe\StripeService;
use App\Service\Stripe\StripeSubscribeService;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Stripe\Price;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Stripe\Stripe as Stripe;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session as CheckoutSession;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @Route("/paiement", name="payment_")
 */
class PaymentController extends AbstractController
{
    /**
     * @Route("/valider", name="success")
     */

    public function success(
        SessionInterface $session,
        StripeService $stripeService,
        Request $request,
        SecuringPurchasesRepository $securingPurchasesRepository,
        BasketService $basketService,
        ResourceRepository $resourceRepository,
        EntityManagerInterface $entityManager
    ): Response {

        $session->set('basket', []);

        $securingPurchases = $this->getDoctrine()
            ->getRepository(SecuringPurchases::class)
            ->findBy(['user' => $this->getUser()]);
        $securingPurchase = end($securingPurchases);

        $token = $_GET['token'];
        $shoppingId = $_GET['achat'];

        $date = new \DateTime('now');

        $purchasedResource = '';

        if (($token === $securingPurchase->getIdentifier()) && ($date < $securingPurchase->getExpirationAt())) {
            $purchasedResource = $this->getDoctrine()
                ->getRepository(Resource::class)
                ->findOneBy(['id' => $shoppingId]);
        }

        $shopping = new Shopping();
        $shopping->setName($purchasedResource->getName());
        $shopping->setOwner($purchasedResource->getUser()->getEmail());
        $shopping->setAmount($purchasedResource->getPrice());
        $shopping->setBuyer($this->getUser()->getUsername());
        $shopping->setType('Achat');
        $entityManager->persist($shopping);
        $entityManager->flush();

        return $this->render('basket/success.html.twig', [
            'purchased_resource' => $purchasedResource,
        ]);
    }

    /**
     * @Route("/abonnement/valider/", name="subscription_success")
     */

    public function subscriptionSuccess(
        StripeSubscribeService $stripeSubscribeService,
        EntityManagerInterface $entityManager
    ): Response {

        $statusSubscriber = $stripeSubscribeService->changeStatusForSubscriber();

        Stripe::setApiKey($this->getParameter('api_key'));
        $companyPrice =  $this->getParameter('company_price');
        $unitAmountCompany = Price::retrieve($companyPrice, []);
        $priceCompany = $unitAmountCompany['unit_amount'] / 100;
        $freelancerPrice =  $this->getParameter('freelancer_price');
        $unitAmountFreelancer = Price::retrieve($freelancerPrice, []);
        $priceFreelancer = $unitAmountFreelancer['unit_amount'] / 100;


        $shopping = new Shopping();
        $shopping->setName('S.O');
        $shopping->setOwner('S.O');
        if (in_array("ROLE_FREELANCER_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setAmount($priceFreelancer);
        } elseif (in_array("ROLE_COMPANY_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setAmount($priceCompany);
        }
        $shopping->setBuyer($this->getUser()->getUsername());
        if (in_array("ROLE_FREELANCER_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setType('Abonnement Freelancer');
        } elseif (in_array("ROLE_COMPANY_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setType('Abonnement Société');
        }
            $entityManager->persist($shopping);
            $entityManager->flush();


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
