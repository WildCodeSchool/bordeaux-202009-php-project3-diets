<?php


namespace App\Service\Basket;


use App\Repository\ResourceRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BasketService
{
    protected $session;
    protected $resourceRepository;

    public function __construct(SessionInterface $session, ResourceRepository $resourceRepository)
    {
        $this->session = $session;
        $this->resourceRepository = $resourceRepository;
    }

    public function add(int $id)
    {



        $basket = $this->session->get('basket');
        if (empty($basket)) {
            $basket[$id] = 1;
        }



        $this->session->set('basket', $basket);

    }

    public function delete(int $id)
    {
        $basket = $this->session->get('basket', []);

        if (!empty($basket[$id])) {
            unset($basket[$id]);
        }

        $this->session->set('basket', $basket);

    }

    public function getAllBasket(): array
    {
        $basket = $this->session->get('basket');
        $basketData= [];

       if(isset ($basket)) {
           foreach ($basket as $id => $quantity) {
               $basketData[] = [
                   'resource' => $this->resourceRepository->find($id),
                   'quantity' => $quantity
               ];
           }
       }
        return $basketData;

    }

    public function getTotal(): float
    {

        $total = 0;
        $basketData = $this->getAllBasket();


        foreach ($basketData as $product) {
            if (empty($product['resource'])) {

            } else {
            $total += $product['resource']->getPrice() * $product['quantity'];
            }
        }

        return $total;

    }

}
