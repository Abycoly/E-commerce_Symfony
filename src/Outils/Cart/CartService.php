<?php

namespace App\Outils\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
	protected $session;
	protected $productRepository;

	public function __construct(SessionInterface $session, ProductRepository $productRepository)
	{
		$this->session = $session;
		$this->productRepository = $productRepository;
	}

	public function add(int $id)
	{
		$panier = $this->session->get('panier', []);
		if (!empty($panier[$id])) {
			$panier[$id]++;
		} else {
			$panier[$id] = 1;
		}
		//$this->session->set('panier', $panier);
		$this->updateCart($panier);
	}

	public function removeItem(int $id)
	{
		$panier = $this->session->get('panier', []);

		if (!empty($panier[$id])) {
			unset($panier[$id]);
		}

		//$this->session->set('panier', $panier);
		$this->updateCart($panier);
	}

	public function remove()
	{
		$this->session->set('panierData', []);
		return $this->session->remove('panier');
	}
	public function updateCart($panier)
	{
		// VARIABLES DE SESSION
		$this->session->set('panier', $panier);
		$this->session->set('panierData', $this->getFullCart());
	}

	public function getFullCart(): array
	{
		$panier = $this->session->get('panier', []);
		$panierWhitData = $this->session->get('panierData', []);
		$panierWhitData = [];

		foreach ($panier as $id => $quantity) {
			$product_object = $this->productRepository->find($id);

			//securité-add si id inconnu
			if (!$product_object) {
				$this->removeItem($id);
				continue;
			}
			//condition si qty selectionné > qty produit 
			if ($quantity > $product_object->getQuantity()) {
				$quantity = $product_object->getQuantity();
				//le nbr de produit selectionné sera égale à la quantity de produit en bdd
				$panier[$id] = $quantity;
				//m.a.j du panier
				$this->updateCart($panier);
			}

			$panierWhitData[] = [
				'product' => $this->productRepository->find($id),
				'quantity' => $quantity
			];
		}

		return $panierWhitData;
		//dd($panierWhitData);
	}

	public function getTotal(): float
	{
		$total = 0;

		foreach ($this->getFullCart() as $item) {
			$totalItem = $item['product']->getPrice() * $item['quantity'];
			$total += $totalItem / 100;
		}

		return $total;
	}

	public function decrease($id)
	{
		$panier = $this->session->get('panier', []);

		if ($panier[$id] > 1) {
			//retirer une quantité
			$panier[$id]--;
		} else {
			//supprimer le produits
			unset($panier[$id]);
		}

		//$this->session->set('panier', $panier);
		$this->updateCart($panier);
	}

	public function get()
	{

		return $this->session->get('panier');
	}
}