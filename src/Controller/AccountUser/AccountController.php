<?php

namespace App\Controller\AccountUser;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AccountController extends AbstractController
{

	/**
	 * @Route("/compte", name="account")
	 */
	public function index(): Response //$encoder->encodePassword
	{
		//$username= $user->getName();
		return $this->render('account/index.html.twig');
	}
}