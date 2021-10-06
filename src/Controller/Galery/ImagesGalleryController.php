<?php

namespace App\Controller\Galery;

use App\Data\SearchData;
use App\Entity\ImagesGallery;
use App\Form\SearchVideoProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImagesGalleryController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	/**
	 * @Route("/galerie/photos", name="pictures_gallery")
	 */
	public function index(Request $request): Response
	{
		//filtre
		$data = new SearchData();
		$data->page = $request->get('page', 1);
		$form = $this->createForm(SearchVideoProduct::class, $data);
		$form->handleRequest($request);
		//fin filtre


		if ($form->isSubmitted() && $form->isValid()) {
			$images = $this->entityManager->getRepository(ImagesGallery::class)->findSearch($data);
		} else {
			$images = $this->entityManager->getRepository(ImagesGallery::class)->findAll();
		}


		return $this->render('images_gallery/index.html.twig', [
			'images' => $images,
			'form' => $form->createView()

		]);
	}
}