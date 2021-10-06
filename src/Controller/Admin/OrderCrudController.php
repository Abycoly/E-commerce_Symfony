<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Outils\Mailjet\Mail;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
	private $entityManager;
	private $crudUrlGenerator;

	public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)
	{
		$this->entityManager = $entityManager;
		$this->crudUrlGenerator = $crudUrlGenerator;
	}

	public static function getEntityFqcn(): string
	{
		return Order::class;
	}

	public function configureActions(Actions $actions): Actions
	{
		$updatePreparation = Action::new('updatePreparation', 'Preparation en cours', 'fas fa-box-open')->linkToCrudAction('updatePreparation');
		$updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')->linkToCrudAction('updateDelivery');

		return $actions
			//ajoute les actions ci-dessous
			->add('index', 'detail')
			->add('detail', $updatePreparation)
			->add('detail', $updateDelivery)
			//supprime les actions suivantes sur mes orders
			->remove('index', 'edit')
			->remove('detail', 'edit')
			->remove('index', 'new')
			->remove('index', 'delete')
			->remove('detail', 'delete');
	}

	public function updatePreparation(AdminContext $context)
	{
		$order = $context->getEntity()->getInstance();
		$order->setState(2);
		$this->entityManager->flush();

		$this->addFlash('notice', '<b class="p-3 mb-2 bg-info text-white">La commande ' . $order->getReference() . ' est en cours de préparation</b>');

		$url = $this->crudUrlGenerator->build()
			->setController(OrderCrudController::class)
			->setAction('index')
			->generateUrl();

		return $this->redirect($url);
	}

	public function updateDelivery(AdminContext $context)
	{
		$order = $context->getEntity()->getInstance();
		$order->setState(3);
		$this->entityManager->flush();

		$this->addFlash('notice', '<b class="p-3 mb-2 bg-success text-white";">La commande ' . $order->getReference() . ' est en cours de livraison </b>');

		$url = $this->crudUrlGenerator->build()
			->setController(OrderCrudController::class)
			->setAction('index')
			->generateUrl();

		$email = new Mail;
		$content = 'Bonjour ' . $order->getUser()->getLastName() . "<br/>Votre commande n°" . $order->getReference() . " vient d'être expédiée par " . $order->getCarrierName() . "<br/> Vous allez recevoir un mail de votre transporteur .</br>";
		$email->send($order->getUser()->getEmail(), $order->getUser()->getLastName(), 'Votre commande est expédiée ', $content);

		return $this->redirect($url);
	}

	public function configureCrud(Crud $crud): Crud
	{
		return $crud->setDefaultSort(['id' => 'DESC']);
	}

	//fonction qui permet d'afficher uniquement les commandes qui ont 1 statut superieur à 0 (non payé)
	public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
	{
		parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

		$response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
		$response->where('entity.state > 0');


		return $response;
	}


	public function configureFields(string $pageName): iterable
	{
		return [
			IdField::new('id'),
			DateTimeField::new('createdAt', 'Passé le : '),
			TextField::new('user.fullname', 'Utilisateurs'),
			TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
			MoneyField::new('total')->setCurrency('EUR'),
			TextField::new('carrierName', 'Transporteur'),
			MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
			ChoiceField::new('state')->setChoices([
				'Non payée' => 0,
				'Payée' => 1,
				'Préparation en cours' => 2,
				'Livraison en cours' => 3
			]),
			ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()
			// TextField::new('title'),
			// TextEditorField::new('description'),
		];
	}
}