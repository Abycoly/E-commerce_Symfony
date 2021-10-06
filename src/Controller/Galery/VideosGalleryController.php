<?php

namespace App\Controller\Galery;

use App\Data\SearchData;
use App\Entity\VideosGallery;
use App\Form\SearchVideoProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideosGalleryController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	/**
	 * @Route("/galerie/videos", name="videos_gallery")
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
			$videos = $this->entityManager->getRepository(VideosGallery::class)->findSearch($data);
			$results = $this->entityManager->getRepository(VideosGallery::class)->findSearch($data);

		} else {
			$results=null;
			$videos = $this->entityManager->getRepository(VideosGallery::class)->findAll();	
		}
		
		return $this->render('videos_gallery/index.html.twig', [
			'videos' => $videos,
			'results'=>$results,
			'form' => $form->createView()
		]);
	}
}