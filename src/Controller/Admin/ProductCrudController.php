<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Gedmo\Mapping\Annotation\Slug;
use App\Repository\ProductRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class ProductCrudController extends AbstractCrudController
{
	public static function getEntityFqcn(): string
	{
		return Product::class;
	}

	public function configureFields(string $pageName): iterable
	{
		return [
			IdField::new('id')->hideOnForm(),
			// TextField::new('title'),
			// TextEditorField::new('description'),
			TextField::new('title'),
			TextareaField::new('description'),
			ImageField::new('media')->setBasePath('uploads')->setUploadDir('public/uploads')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
			MoneyField::new('price')->setCurrency('EUR'),
			IntegerField::new('size'),
			BooleanField::new('isBest'),
			BooleanField::new('isNewArrival'),
			BooleanField::new('isSpecialOffert'),
			TextField::new('color'),
			IntegerField::new('quantity'),
			AssociationField::new('category'),
			//AssociationField::new('relatedProducts'),
			SlugField::new('slug')->setTargetFieldName('title')
		];
	}
}