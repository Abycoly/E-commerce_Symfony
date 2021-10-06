<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Outils\Mailjet\Mail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
	/**
	 * @Route("/nous-contacter", name="contact")
	 */
	public function index(Request $request): Response
	{
		$form = $this->createForm(ContactType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');

			$mail = new Mail;
			$content = 'Expéditeur : ' . $form->get('nom')->getData() . ' ' . $form->get('prenom')->getData() . '<br/>';
			$content .= 'Contact mail : ' . $form->get('email')->getData() . '<br/>';
			$content .= 'Message : ' . $form->get('content')->getData();
			$mail->send('aby.creation@gmail.com', 'A.C. Beauty', 'Nouvel demande de contact', $content);
		}
		return $this->render('contact/index.html.twig', [
			'form' => $form->createView()
		]);
	}
}