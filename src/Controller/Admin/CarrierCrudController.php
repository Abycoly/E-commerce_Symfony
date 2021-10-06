<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarrierCrudController extends AbstractCrudController
{
	public static function getEntityFqcn(): string
	{
		return Carrier::class;
	}


	// public function configureFields(string $pageName): iterable
	// {
	// 	// return [
	// 	// 	//IdField::new('id'),
	// 	// 	TextField::new('name', 'nom du transporteur'),
	// 	// 	MoneyField::new('price', 'prix')->setCurrency('EUR'),
	// 	// 	TextField::new('description', 'descrition')->hideOnIndex(),

	// 	];
	// }
}