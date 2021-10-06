<?php

namespace App\Controller;


use Stripe\Stripe;
use App\Entity\Order;

use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class StripeController extends AbstractController
{
	/**
	 * @Route("/commande/create-session/{reference}", name="stripe_create_session")
	 */
	public function index(EntityManagerInterface $entityManager, $reference): Response
	{

		$product_for_stripe = [];
		$YOUR_DOMAIN = 'http://127.0.0.1:8000';

		$order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
		if (!$order) {
			new JsonResponse(['error' => 'order']);
		}
		//dd($order->getOrderDetails()->getValues());

		//remplissage de notre variable $product_for_stripe = [] qui va contenir notre commande détaillé 
		foreach ($order->getOrderDetails()->getValues() as $product) {

			$product_object = $entityManager->getRepository(Product::class)->findOneByTitle($product->getProduct());
			$product_for_stripe[] = [
				'price_data' => [
					'currency' => 'eur',
					'unit_amount' => $product->getPrice(),
					'product_data' => [
						'name' => $product->getProduct(),
						'images' => [$YOUR_DOMAIN . "/uploads/" . $product_object->getMedia()],
					],
				],
				'quantity' => $product->getQuantity(),
			];
		}

		$product_for_stripe[] = [
			'price_data' => [
				'currency' => 'eur',
				'unit_amount' => $order->getCarrierPrice(),
				'product_data' => [
					'name' => $order->getCarrierName(),
					'images' => [],
				],
			],
			'quantity' => 1,
		];

		//dd($product_for_stripe);

		Stripe::setApiKey('sk_test_51IWf6BJFxnhFIa0FcSCg7k63L9zJPBClpunOf841qC6Hv4re9KqqfZ9cDgVR0vvUCKr1Sl4hx5XAojoD6pDtUyWi009VkgYAzp');

		// Session::create va générer un Id de session pr Stripe
		$checkout_session = Session::create([
			'customer_email' => $this->getUser()->getEmail(),
			'payment_method_types' => ['card'],
			'line_items' => [[
				$product_for_stripe
			]],
			'mode' => 'payment',
			'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
			'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
		]);



		$order->setStripeSessionId($checkout_session->id);
		$entityManager->flush();

		$reponse = new JsonResponse(['id' => $checkout_session->id]);

		return $reponse;
	}
}