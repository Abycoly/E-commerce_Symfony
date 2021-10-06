<?php

namespace App\Outils\StockManager;

use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StockManagerServices
{

	private $entityManager;
	private $productRepository;


	public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository)
	{
		$this->entityManager = $entityManager;
		$this->productRepository = $productRepository;
	}

	public function deStock(Order $order)
	{
		$orderDetails = $order->getOrderDetails()->getValues();

		foreach ($orderDetails as $key => $details) {
			$product = $this->productRepository->findByTitle($details->getProduct())[0];
			$newqty = $product->getQuantity() - $details->getQuantity();
			$product->setQuantity($newqty);
			$this->entityManager->flush();
		}
	}
}