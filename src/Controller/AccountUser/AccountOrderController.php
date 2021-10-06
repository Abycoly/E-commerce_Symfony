<?php

namespace App\Controller\AccountUser;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/commande", name="account_order")
     */
    public function index()
    {
        $orders = $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());
        //dd($orders);
        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/compte/commande/{reference}", name="account_order_show")
     */
    public function show($reference)
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByReference($reference);
        //dd($orders);
		
		// securité / si reference inexistante ou si reference commande n'appartient pas à l'utilisateur 
        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_order');
        }
        return $this->render('account/order_show.html.twig', [
            'order' => $order
        ]);
    }
}