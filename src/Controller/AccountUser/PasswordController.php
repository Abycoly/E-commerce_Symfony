<?php

namespace App\Controller\AccountUser;

use App\Form\ModifPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    // on construit un objet de type  EntityManagerInterface, on accede dc a ttes les methodes de cette classe au travers de cette objet
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/edit-password", name="edit-password")
     */
    public function editPaswword(Request $request,  UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ModifPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on cherche l'ancien mdp envoyé qui est ds la bdd
            $oldPassword = $form->get('old-Password')->getData();
            //on verifie si le mdp est bien celui de notre user
            if ($encoder->isPasswordValid($user, $oldPassword)) {

                $newPassword = $form->get('newPassword')->getData();
                $password = $encoder->encodePassword($user, $newPassword);
                $user->setPassword($password);
                //pas besoin du persist car on est ds une modification
                //$this->entityManager->persist($user);
                $this->entityManager->flush();
            }
            $this->addFlash(
                'info',
                'Votre modification a bien été prise en compte.'
            );
        }

        return $this->render('account/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}