<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('label', TextType::class, [
				'label' => 'Quel nom souhaitez vous donner à votre adresse ?',
				'attr' => [
					'placeholder' => 'Nommez votre adresse'
				]
			])
			->add('firstname', TextType::class, [
				'label' => 'Votre nom ',
				'attr' => [
					'placeholder' => 'Entrez votre nom'
				]
			])
			->add('lastname', TextType::class, [
				'label' => 'Votre prénom ',
				'attr' => [
					'placeholder' => 'Entrez votre prénom'
				]
			])
			->add('compagny', TextType::class, [
				'label' => 'Le nom de la compagnie ',
				'required' => false,
				'attr' => [
					'placeholder' => '(facultatif) Entrez le nom de la compagnie'
				]
			])
			->add('address', TextType::class, [
				'label' => 'Votre adresse ',
				'attr' => [
					'placeholder' => 'Entrez votre adresse'
				]
			])
			->add('city', TextType::class, [
				'label' => 'Ville',
				'attr' => [
					'placeholder' => 'Entrez votre ville'
				]
			])
			->add('postal_code', TextType::class, [
				'label' => 'Code postal',
				'attr' => [
					'placeholder' => 'Entrez votre code postal'
				]
			])
			->add('country', CountryType::class, [
				'label' => 'Pays',
				'attr' => [
					'placeholder' => 'Votre Pays'
				]
			])
			->add('phone_number', TelType::class, [
				'label' => 'Numéro de téléphone',
				'attr' => [
					'placeholder' => 'Entrez votre téléphone'
				]
			]);
		//->add('user');
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Address::class,
		]);
	}
}
