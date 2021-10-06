<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;

use App\Outils\Mailjet\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

	private $entityManager;

	// on construit un objet de type  EntityManagerInterface, on accede dc a ttes les methodes de cette classe au travers de cette objet
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @Route("/inscription", name="register")
	 */
	public function index(Request $request, UserPasswordEncoderInterface $encoder): Response


	{
		//on istancie la classe User
		$user = new User();

		//on instancie le formulaire avec la methode CreateForm
		$form = $this->createForm(RegisterType::class, $user);

		// $form = $this->createForm(RegisterType::class, $user, [
		//     'validation_groups' => ['registration']
		// ]);

		//on utilise handleRequest (pr ecouter la requete)
		$form->handleRequest($request);

		//on verifie si notre formulaire est envoyé et valide
		if ($form->isSubmitted() && $form->isValid()) {
			$user = $form->getData();

			$search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

			//condition de vérification d'email deja existant ds la bdd
			if (!$search_email) {

				$password = $encoder->encodePassword($user, $user->getPassword());
				$user->setPassword($password);
				//pr encoder le password, on utilise la méthode encodePassword de la classe UserPasswordEncoderInterface, stocké ds la variable $encoder. Cette methode prend les parametres $user(=la classe User) et getPassword de la classe User.

				//on modifie le password avec la methode setPassword qui a pr parametres $password(=password encoder).

				$this->entityManager->persist($user);
				$this->entityManager->flush();
				//pr enregistrer les données ds la bdd

				//ENVOI MAIL DE CONFIRMATION DINSCRIPTION
				$mail = new Mail;
				$content = 'Bonjour ' . $user->getName() . "<br/>Bienvenue chez A.C Beauty, votre inscription à bien été prise en compte</br>";
				$mail->send($user->getEmail(), $user->getName(), 'Bienvenue chez A.C Beauty', $content);

				$this->addFlash(
					'info',
					'Votre inscription a bien été prise en compte.'
				);
			} else {
				$this->addFlash(
					'alert',
					'L\'email que vous avez renseigné existe déja.'
				);
			}
		}


		return $this->render('register/index.html.twig', [
			'form' => $form->createView()
		]);
	}
}