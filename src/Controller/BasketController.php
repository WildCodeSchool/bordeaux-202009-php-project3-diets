<?php

namespace App\Controller;


use App\Entity\Resource;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/basket", name="basket_")
 */
class BasketController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ResourceRepository $resourceRepository): Response
    {
      $basket = $session->get('basket', []);


        $basketData = [];

        foreach($basket as $id => $quantity) {
            $basketData[] = [
                'resource' => $resourceRepository->find($id),
                'quantity' => $quantity
            ];
        }

      $total = 0;

        foreach ($basketData as $product) {
           $totalProduct = $product['resource']->getPrice() * $product['quantity'];
           $total += $totalProduct;
        }

        return $this->render('basket/index.html.twig', [
            'products' => $basketData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(SessionInterface $session, $id)
    {
        $basket = $session->get('basket', []);

        if(!empty($basket[$id])) {
            $basket[$id]++;
        } else {
            $basket[$id] = 1;
        }

        $session->set('basket', $basket);

        dd($basket);

    }
}
