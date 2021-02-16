<?php

namespace App\Controller;


use App\Service\Basket\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/basket", name="basket_")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(BasketService $basketService): Response
    {
        $basketData = $basketService->getAllBasket();

        $total = $basketService->getTotal();

        return $this->render('basket/index.html.twig', [
            'products' => $basketData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(BasketService $basketService, int $id)
    {
        $basketService->add($id);

        return $this->redirectToRoute('knowledge_index');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, BasketService $basketService)
    {
        $basketService->delete($id);

        return $this->redirectToRoute('basket_index');

    }
}
