<?php

namespace App\Controller\Order;


use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use App\Outils\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/commande")
 */
class OrderController extends AbstractController
{
	protected $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * @Route("/", name="order")
	 */
	public function index(CartService $cart): Response
	{
		//si aucune addresses n'est recupérer-> on retourne vers la route address_new pr crer celle ci
		if(!$cart->getFullCart()){
			return $this->redirectToRoute('products');
		}
		if (!$this->getUser()->getAddresses()->getValues()) {
			return $this->redirectToRoute('address_new');
		}

		//on cree notre form avec les adresses de l'utilisateur
		$form = $this->createForm(OrderType::class, null, [
			'user' => $this->getUser()
		]);


		return $this->render('order/index.html.twig', [
			'form' => $form->createView(),
			'cart' => $cart->getFullCart()
		]);
	}

	/**
	 * @Route("/recapitulatif", name="order_recap", methods={"POST"})
	 */
	public function add(CartService $cart, Request $request): Response
	{
		// securité url / si panier vide redirection vers home
		if(!$cart->getFullCart()){
			return $this->redirectToRoute('products');
		}
		
		$form = $this->createForm(OrderType::class, null, [
			'user' => $this->getUser()
		]);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$date = new \DateTime();
			$carriers = $form->get('carriers')->getData();
			$delivery = $form->get('addresses')->getData();
			$delivery_content = $delivery->getFirstname() . ' ' . $delivery->getLastname();
			$delivery_content .= '<br/>' . $delivery->getPhoneNumber();

			if ($delivery->getCompagny()) {
				$delivery_content .= '<br/>' . $delivery->getCompagny();
			}
			$delivery_content .= '<br/>' . $delivery->getAddress();
			$delivery_content .= '<br/>' . $delivery->getPostalCode();
			$delivery_content .= '<br/>' . $delivery->getCountry();

			// Enregistrer ma commande Order()
			$order = new Order();
			//on initialise la valeur de référence commande qui sera la date + 1 id unique 
			$reference = $date->format('dmY') . '-' . uniqid();
			$order->setReference($reference);
			$order->setUser($this->getUser());
			$order->setCreatedAt($date);
			$order->setCarrierName($carriers->getName());
			$order->setCarrierPrice($carriers->getPrice());
			$order->setDelivery($delivery_content);
			$order->setState(0);

			$this->entityManager->persist($order);

			// Enregistrer mes produits OrderDetails()

			foreach ($cart->getFullCart() as $product) {
				$orderDetailts = new OrderDetails();
				$orderDetailts->setMyOrder($order);
				$orderDetailts->setProduct($product['product']->getTitle());
				$orderDetailts->setQuantity($product['quantity']);
				$orderDetailts->setPrice($product['product']->getPrice());
				$orderDetailts->setTotal($product['product']->getPrice() * $product['quantity']);


				$this->entityManager->persist($orderDetailts);
			}

			$this->entityManager->flush();

			return $this->render('order/add.html.twig', [
				'cart' => $cart->getFullCart(),
				'carrier' => $carriers,
				'delivery' => $delivery_content,
				'reference' => $order->getReference()
			]);
		}

		return $this->redirectToRoute('cart');
	}
}