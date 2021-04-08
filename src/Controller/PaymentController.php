<?php

namespace App\Controller;

use App\Entity\Resource;
use App\Entity\SecuringPurchases;
use App\Entity\Shopping;
use App\Repository\UserRepository;
use App\Service\Stripe\StripeService;
use App\Service\Stripe\StripeSubscribeService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Price;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Stripe\Stripe as Stripe;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/paiement", name="payment_")
 */
class PaymentController extends AbstractController
{
    protected const NONE = 'NONE';
    public const FREELANCER_SUBSCRIBE = 'Abonnement Freelancer';
    public const COMPANY_SUBSCRIBE = 'Abonnement Société';
    public const IDENTIFIERACHAT = 'achat';

    /**
     * @Route("/valider", name="success")
     */

    public function success(
        SessionInterface $session,
        EntityManagerInterface $entityManager
    ): Response {

        $session->set('basket', []);

        $securingPurchases = $this->getDoctrine()
            ->getRepository(SecuringPurchases::class)
            ->findBy(['user' => $this->getUser()]);
        $securingPurchase = end($securingPurchases);

        $token = $_GET['token'];
        $shoppingId = $_GET[self::IDENTIFIERACHAT];

        $date = new \DateTime('now');

        $purchasedResource = '';

        if (($token === $securingPurchase->getIdentifier()) && ($date < $securingPurchase->getExpirationAt()) && ($shoppingId === $securingPurchase->getResource())) {
            $purchasedResource = $this->getDoctrine()
                ->getRepository(Resource::class)
                ->findOneBy(['id' => $shoppingId]);
        }

        $shopping = new Shopping();
        $shopping->setName($purchasedResource->getName());
        $shopping->setOwner($purchasedResource->getUser()->getEmail());
        $shopping->setAmount($purchasedResource->getPrice());
        $shopping->setBuyer($this->getUser()->getUsername());
        $shopping->setType(self::IDENTIFIERACHAT);
        $entityManager->persist($shopping);
        $entityManager->flush();

        return $this->render('basket/success.html.twig', [
            'purchased_resource' => $purchasedResource,
        ]);
    }

    /**
     * @Route("/inscription/valider/", name="register_success")
     */

    public function registerSuccess(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        if ($this->getUser() !== null) {
            $userRegister = $userRepository->findOneBy(['id' => $this->getUser()->getId()]);
            if (in_array('ROLE_DIETETICIAN', $userRegister->getRoles(), $strict = true)) {
                $userRegister->setRoles(['ROLE_DIETETICIAN_REGISTER']);
                $entityManager->persist($userRegister);
                $entityManager->flush();
            }
        }
        return $this->render('payment/register_success.html.twig', [
            'controller_name' => 'PaymentController',
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
        $shopping->setName(self::NONE);
        $shopping->setOwner(self::NONE);
        if (in_array("ROLE_FREELANCER_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setAmount($priceFreelancer);
        } elseif (in_array("ROLE_COMPANY_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setAmount($priceCompany);
        }
        $shopping->setBuyer($this->getUser()->getUsername());
        if (in_array("ROLE_FREELANCER_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setType(self::FREELANCER_SUBSCRIBE);
        } elseif (in_array("ROLE_COMPANY_SUBSCRIBER", $this->getUser()->getRoles())) {
            $shopping->setType(self::COMPANY_SUBSCRIBE);
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
