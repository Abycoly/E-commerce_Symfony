<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Header;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\ImagesGallery;
use App\Entity\VideosGallery;
use App\Controller\AccountController;
use App\Controller\Admin\OrderCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;




class DashboardController extends AbstractDashboardController
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function index(): Response
	{
		// redirect to some CRUD controller
		$routeBuilder = $this->get(AdminUrlGenerator::class);

		return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
	}

	public function configureDashboard(): Dashboard
	{
		return Dashboard::new()
			->setTitle('A.C Beauty')
			->setFaviconPath('../../../images/ACBeauty.jpg');
	}

	public function configureMenuItems(): iterable
	{
		// yield MenuItem::linktoRoute('compte', 'fas fa-list', );
		yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
		yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
		yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart', Order::class);
		yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
		yield MenuItem::linkToCrud('Produits', 'fas fa-tag', Product::class);
		yield MenuItem::linkToCrud('Transporteur', 'fas fa-truck', Carrier::class);
		yield MenuItem::linkToCrud('Header', 'fas fa-desktop', Header::class);
		yield MenuItem::linkToCrud('Galerie Videos', 'fas fa-photo-video', VideosGallery::class);
		// yield MenuItem::linkToCrud('Galerie photos', 'fas fa-photo-video', ImagesGallery::class);
		yield MenuItem::linktoRoute('retour au compte', 'fas fa-home', 'account');
	}

	public function configureUserMenu(UserInterface $user): UserMenu
	{
		return parent::configureUserMenu($user)

			->setName($user->getUsername());
	}
}