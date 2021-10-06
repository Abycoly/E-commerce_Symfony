<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Outils\Mailjet\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderCancelController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @Route("/commande/erreur/{stripeSessionId}", name="order_cancel")
	 */
	public function index($stripeSessionId): Response
	{
		$order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

		//securité // si la commande d'existe pas ou si le userId de la commande est différent de l'userId connecté -> redirection vers home
		if (!$order || $order->getUser() != $this->getUser()) {
			return $this->redirectToRoute('home');
		}

		//envoyer 1 email a notre utilisateur pr lui indiquer l'echec de paiement
		$mail = new Mail;
		$content = 'Bonjour ' . $order->getUser()->getLastName() . "<br/>Il semblerait que votre paiement pour la commande n°" . $order->getReference() . " est echoué. <br/> Retrouvez l'ensemble des produits que vous souhaitiez commander dans votre panier. Ils vous attendent .</br>";
		$mail->send($order->getUser()->getEmail(), $order->getUser()->getLastName(), 'Echec de paiement pour votre commande chez A.C Beauty', $content);

		return $this->render('order_cancel/index.html.twig', [
			'order' => $order
		]);
	}
}