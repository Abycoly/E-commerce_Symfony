<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\OrderDetails;
use App\Outils\Mailjet\Mail;
use Doctrine\ORM\EntityManager;
use App\Outils\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Outils\StockManager\StockManagerServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	/**
	 * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
	 */
	public function index(CartService $cart, $stripeSessionId, StockManagerServices $stockManagerServices): Response
	{

		$order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

		//securité // si la commande d'existe pas ou si le userId de la commande est différent de l'userId connecté -> redirection vers home
		if (!$order || $order->getUser() != $this->getUser()) {
			return $this->redirectToRoute('home');
		}


		//si isPaid ma commande est en statut non payé ... dc si isPaid=0
		if ($order->getState() == 0) {
			//Vider la session "cart"
			$cart->remove();

			//modifier le status isPaid de notre commande en mettant 1
			$order->setState(1);

			//destockage produits commander
			$stockManagerServices->deStock($order);

			$this->entityManager->flush();

			//envoyer 1 email a notre client pr confirmer sa commande
			$mail = new Mail;
			$content = 'Bonjour ' . $order->getUser()->getLastName() . "<br/>Merci pour votre commande n°" . $order->getReference() . ".<br/> Retrouvez le détail de votre commande et son suivi dans votre espace personel .</br>";
			$mail->send($order->getUser()->getEmail(), $order->getUser()->getLastName(), 'Votre commande chez A.C Beauty', $content);
		}


		//afficher les quelque infos de la commande du user


		return $this->render('order_success/index.html.twig', [
			'order' => $order
		]);
	}
}