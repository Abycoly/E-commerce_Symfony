<?php

namespace App\Controller\Admin;

use App\Entity\VideosGallery;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VideosGalleryCrudController extends AbstractCrudController
{
	public static function getEntityFqcn(): string
	{
		return VideosGallery::class;
	}


	public function configureFields(string $pageName): iterable
	{
		return [
			// IdField::new('id'),
			TextField::new('title'),
			TextField::new('url'),
			ImageField::new('image')->setBasePath('uploadsImgVideo')->setUploadDir('public/uploadsImgVideo')->setUploadedFileNamePattern('[randomhash].[extension]')->setRequired(false),
			AssociationField::new('product')
		];
	}
}