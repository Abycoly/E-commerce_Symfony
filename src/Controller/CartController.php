<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use App\Outils\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
	protected $session;
	protected $entityManager;

	public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
	{
		$this->session = $session;
		$this->entityManager = $entityManager;
	}
	/**
	 * @Route("/panier", name="cart")
	 */
	public function index(CartService $cartService, Request $request): Response
	{

		return $this->render('cart/index.html.twig', [
			'items' => $cartService->getFullCart(),
			'total' => $cartService->getTotal(),

		]);
	}

	/**
	 * @Route("/panier/add/{id}", name="cart-add")
	 */
	public function add($id, CartService $cartService, Request $request)
	{
		$product = $this->entityManager->getRepository(Product::class)->find($id);
		$product_quantity = $product->getQuantity();

		if ($product_quantity == 0) {

			$this->addFlash('notice', 'Produit indisponible');
			return $this->redirectToRoute("products");
		} else {
			$cartService->add($id);
			//return $this->redirectToRoute("cart");
			$referer = $request->headers->get('referer');
			$this->addFlash('notice', 'Produit ajouté au panier');
			//redirection sur la page actuelle
			return $this->redirect($referer);
		}
	}

	/**
	 * @Route("/panier/remove/{id}", name="remove-to-cart")
	 */
	public function removeItem($id, CartService $cartService, Request $request)
	{

		$cartService->removeItem($id);

		$referer = $request->headers->get('referer');

		$panier = $this->session->get('panier');

		if (!$panier) {
			// si panier vide-->on redirige vers page produits
			return $this->redirectToRoute('products');
		} else {
			//sinon on reste sur la page actuelle
			return $this->redirect($referer);
		}
		//return $this->redirectToRoute("cart");
	}
	//test
	/**
	 * @Route("/panier/remove", name="cart-remove")
	 */
	public function remove(CartService $cartService)
	{
		$cartService->remove([]);

		return $this->redirectToRoute("products");
	}

	/**
	 * @Route("/panier/decrease/{id}", name="decrease-to-cart")
	 */
	public function decrease($id, CartService $cartService, Request $request)
	{
		$cartService->decrease($id);

		$referer = $request->headers->get('referer');

		$panier = $this->session->get('panier');

		if (!$panier) {
			// si panier vide-->on redirige vers page produits
			return $this->redirectToRoute('products');
		} else {
			//sinon on reste sur la page actuelle
			return $this->redirect($referer);
		}
		return $this->redirectToRoute("cart");
	}
}