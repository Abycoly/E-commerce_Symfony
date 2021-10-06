<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class CGUController extends AbstractController
{
	/**
	 * @Route("/conditions-generales-d\'utilisation-utilisateurs", name="conditions")
	 */
	public function index(): Response
	{
		return $this->render('cgu/index.html.twig', []);
	}
}