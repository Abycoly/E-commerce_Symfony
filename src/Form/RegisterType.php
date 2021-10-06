<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;



class RegisterType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class, [
				'label' => 'Nom',
				'constraints' => new length([
					'min' => 2,
					'max' => 30
				]),
				'attr' => [
					'placeholder' => 'Votre Nom'
				]
			])

			->add('lastname', TextType::class, [
				'label' => 'Prénom',
				'constraints' => new length([
					'min' => 2,
					'max' => 30
				]),
				'attr' => [
					'placeholder' => 'Votre Prénom'
				]
			])

			->add('email', EmailType::class, [
				'label' => 'Email',
				'attr' => [
					'placeholder' => 'Veuillez entrer votre Email'
				]
			])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
				'required' => true,
				'constraints' => new length([
					'min' => 8,
					'max' => 40
				]),
				'first_options' => [
					'label' => 'Mot de passe',
					'attr' => [
						'placeholder' => 'Veuillez entrer votre Mot de passe'
					]
				],
				'second_options' => [
					'label' => 'Confirmation de Mot de passe',
					'attr' => [
						'placeholder' => 'Veuillez confirmer votre Mot de passe'
					]
				]

			])
			->add('submit', SubmitType::class, [
				'label' => 'S\'inscrire',
				'attr' => [
					'class' => 'btn-lg btn-info btn-block bg-dark px-auto mt-3'
				]

			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class
		]);
	}
}