<?php

namespace App\Controller;

use App\Entity\Product;
use App\Data\SearchData;
use App\Entity\Category;
use App\Form\SearchForm;
use App\Outils\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{

	private $entityManager;


	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}
	/**
	 * @Route("/nos-produits", name="products")
	 */
	public function index(Request $request, PaginatorInterface $paginator): Response
	{

		//formulaire filtre
		$data = new SearchData();
		// $data->page = $request->get('page', 1);
		$form = $this->createForm(SearchForm::class, $data);
		$form->handleRequest($request);
		//fin form filtre



		if ($form->isSubmitted() && $form->isValid()) {
			$products = $this->entityManager->getRepository(Product::class)->findSearch($data);
			// dd($products);
		} else {
			$products = $this->entityManager->getRepository(Product::class)->findAll();
			//pagination des produits dans le cas ou le formulaire de filtre n'est pas soumis dc aucun filtre n'est effectué
			$products = $paginator->paginate(
				$products,
				$request->query->getInt('page', 1),
				8
			);
		}
		return $this->render('products/index.html.twig', [
			'products' => $products,
			'form' => $form->createView()

		]);
	}

	/**
	 * @Route("/produit/{slug}", name="product_detail")
	 */
	public function show(CartService $cartService, $slug)
	{

		$product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
		$allProduct = $this->entityManager->getRepository(Product::class)->findAll();

		$quantity = $product->getQuantity();
		$stock = '';

		if ($quantity === 0) {
			$stock = '<div class="badge badge-pill badge-danger">Indisponible</div>';
		} else {
			$stock = '<div class="badge badge-pill badge-info">Disponible</div>';
		}


		return $this->render('products/show.html.twig', [
			'product' => $product,
			'items' => $cartService->getFullCart(),
			'stock' => $stock,
			'allProduct' => $allProduct

		]);
	}


	/**
	 * @Route("/categorie/{name}", name="category_products")
	 */
	public function findByCategory($name)
	{
		$categories = $this->entityManager->getRepository(Category::class)->findOneByName($name);

		return $this->render('products/products_category.html.twig', ['categories' => $categories]);
	}
}