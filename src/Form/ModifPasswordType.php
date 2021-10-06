<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ModifPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('old-Password', PasswordType::class, [
                'label' => 'Mon mot de passe actuelle',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'required' => true,
                'mapped' => false,
                'constraints' => new length([
                    'min' => 8,
                    'max' => 40
                ]),
                'first_options' => [
                    'label' => 'Nouveau Mot de passe',
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
                'label' => 'Envoyer',
				'attr' => [
					'class' => 'btn-lg btn-info btn-block bg-dark px-auto mt-3'
				]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}