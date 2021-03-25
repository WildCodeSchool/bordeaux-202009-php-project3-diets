<?php

namespace App\Controller;

use App\Service\Basket\BasketService;
use App\Service\Stripe\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted ("ROLE_ENTERPRISE")
 * @Route("/panier", name="basket_")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(BasketService $basketService, StripeService $stripeService): Response
    {
        $basketData = $basketService->getAllBasket();

        $total = $basketService->getTotal();

        return $this->render('basket/index.html.twig', [
            'products' => $basketData,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/jsonStripe", name="jsonStripe")
     */
    public function jsonStripe(BasketService $basketService, StripeService $stripeService): Response
    {

        $account = $stripeService->getAccountId();

        $publicKey = $this->getParameter('api_public_key');

        $datas = ['publickey' => $publicKey, 'account' => $account];


        return new JsonResponse($datas, 200);
    }

    /**
     * @Route("/ajouter/{id}", name="add")
     */
    public function add(BasketService $basketService, int $id)
    {
        $basketService->add($id);

        return $this->redirectToRoute('basket_index');
    }

    /**
     * @Route("/supprimer/{id}", name="delete")
     */
    public function delete($id, BasketService $basketService)
    {
        $basketService->delete($id);

        return $this->redirectToRoute('basket_index');
    }
}
