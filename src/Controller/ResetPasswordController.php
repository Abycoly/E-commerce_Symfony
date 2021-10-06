<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Outils\Mailjet\Mail;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Symfony\Component\Mime\Address;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;


class ResetPasswordController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @Route("/mot-de-passe-oublie", name="reset_password")
	 */
	public function index(Request $request)
	{
		if ($this->getUser()) {
			return $this->redirectToRoute('home');
		}

		if ($request->get('email')) {
			//dd($request->get('email'));
			$user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
			//dd($user);

			if ($user) {
				//1: enregistrer en bdd la demande de reset_password avec user, token, createdAt
				$reset_password = new ResetPassword();
				$reset_password->setUser($user);
				$reset_password->setToken(uniqid());
				$reset_password->setCreatedAt(new DateTime());
				$this->entityManager->persist($reset_password);
				$this->entityManager->flush();

				//envoyer 1 mail au user avec 1 lien lui permettant de mettre a jour son mot de passe
				$url = $this->generateUrl('update_password', [
					'token' => $reset_password->getToken()
				]);
				$content = 'Bonjour ' . $user->getLastname() . '<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site A.C Beauty.<br/><br/>';
				$content .= "merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'> mettre a jour votre mot de passe <a/>";
				$mail = new Mail;
				$mail->send($user->getEmail(), $user->getName() . ' ' . $user->getLastname(), 'Réinitialiser votre mot de passe', $content);
				$this->addFlash('notice', 'Vous allez recevoir dans un instant un mail avec la procédure pour réinitialiser votre mot de passe.');
			} else {
				$this->addFlash('notice', 'Cette adresse Email est inconnue.');
			}
		}
		return $this->render('reset_password/index.html.twig');
	}


	/**
	 * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
	 */

	public function update(Request $request, UserPasswordEncoderInterface $encoder, $token)
	{
		//dd($token);
		$reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

		if (!$reset_password) {
			return $this->redirectToRoute('reset_password');
		}
		//dd($reset_password);

		//verifier si le createdAt = now-3h
		$now = new DateTime();
		if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
			$this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveller.');
			return $this->redirectToRoute('reset_password');
		}

		//rendre une vue avec mot de passe et confirmez votre mot de passe
		$form = $this->createForm(ResetPasswordType::class);
		$form->handleRequest($request);


		if ($form->isSubmitted() && $form->isValid()) {
			$new_pwd = $form->get('new_password')->getData();
			//dd($form->getData($new_pwd));

			//encodage des mots de passe
			$password = $encoder->encodePassword($reset_password->getUser(), $new_pwd);
			$reset_password->getUser()->setPassword($password);

			//flush en base de donnée // pas besoin du persist car on est ds une modification
			$this->entityManager->flush();

			//redirection de l'utilisateur vers la page de connexion
			$this->addFlash('notice', 'Votre mot de passe a bien éte mis à jour.');
			return $this->redirectToRoute('app_login');
		}





		return $this->render('reset_password/update.html.twig', [
			'form' => $form->createView()
		]);
	}
}
// {
//     use ResetPasswordControllerTrait;

//     private $resetPasswordHelper;


//     public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
//     {
//         $this->resetPasswordHelper = $resetPasswordHelper;
//     }

//     /**
//      * Display & process form to request a password reset.
//      *
//      * @Route("", name="app_forgot_password_request")
//      */
//     public function request(Request $request, MailerInterface $mailer): Response
//     {
//         $form = $this->createForm(ResetPasswordRequestFormType::class);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             return $this->processSendingPasswordResetEmail(
//                 $form->get('email')->getData(),
//                 $mailer
//             );
//         }

//         return $this->render('reset_password/request.html.twig', [
//             'requestForm' => $form->createView(),
//         ]);
//     }

//     /**
//      * Confirmation page after a user has requested a password reset.
//      *
//      * @Route("/check-email", name="app_check_email")
//      */
//     public function checkEmail(): Response
//     {
//         // We prevent users from directly accessing this page
//         if (!$this->canCheckEmail()) {
//             return $this->redirectToRoute('app_forgot_password_request');
//         }

//         return $this->render('reset_password/check_email.html.twig', [
//             'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
//         ]);
//     }

//     /**
//      * Validates and process the reset URL that the user clicked in their email.
//      *
//      * @Route("/reset/{token}", name="app_reset_password")
//      */
//     public function reset(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $token = null): Response
//     {
//         if ($token) {
//             // We store the token in session and remove it from the URL, to avoid the URL being
//             // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
//             $this->storeTokenInSession($token);

//             return $this->redirectToRoute('app_reset_password');
//         }

//         $token = $this->getTokenFromSession();
//         if (null === $token) {
//             throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
//         }

//         try {
//             $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
//         } catch (ResetPasswordExceptionInterface $e) {
//             $this->addFlash('reset_password_error', sprintf(
//                 'There was a problem validating your reset request - %s',
//                 $e->getReason()
//             ));

//             return $this->redirectToRoute('app_forgot_password_request');
//         }

//         // The token is valid; allow the user to change their password.
//         $form = $this->createForm(ChangePasswordFormType::class);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             // A password reset token should be used only once, remove it.
//             $this->resetPasswordHelper->removeResetRequest($token);

//             // Encode the plain password, and set it.
//             $encodedPassword = $passwordEncoder->encodePassword(
//                 $user,
//                 $form->get('plainPassword')->getData()
//             );

//             $user->setPassword($encodedPassword);
//             $this->getDoctrine()->getManager()->flush();

//             // The session is cleaned up after the password has been changed.
//             $this->cleanSessionAfterReset();

//             return $this->redirectToRoute('app_home');
//         }

//         return $this->render('reset_password/reset.html.twig', [
//             'resetForm' => $form->createView(),
//         ]);
//     }

//     private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): RedirectResponse
//     {
//         $user = $this->getDoctrine()->getRepository(User::class)->findOneBy([
//             'email' => $emailFormData,
//         ]);

//         // Marks that you are allowed to see the app_check_email page.
//         $this->setCanCheckEmailInSession();

//         // Do not reveal whether a user account was found or not.
//         if (!$user) {
//             return $this->redirectToRoute('app_check_email');
//         }

//         try {
//             $resetToken = $this->resetPasswordHelper->generateResetToken($user);
//         } catch (ResetPasswordExceptionInterface $e) {
//             // If you want to tell the user why a reset email was not sent, uncomment
//             // the lines below and change the redirect to 'app_forgot_password_request'.
//             // Caution: This may reveal if a user is registered or not.
//             //
//             // $this->addFlash('reset_password_error', sprintf(
//             //     'There was a problem handling your password reset request - %s',
//             //     $e->getReason()
//             // ));

//             return $this->redirectToRoute('app_check_email');
//         }

//         $email = (new TemplatedEmail())
//             ->from(new Address('melle.abycoly@gmail.com', 'aby'))
//             ->to($user->getEmail())
//             ->subject('Your password reset request')
//             ->htmlTemplate('reset_password/email.html.twig')
//             ->context([
//                 'resetToken' => $resetToken,
//             ]);

//         $mailer->send($email);

//         return $this->redirectToRoute('app_check_email');
//     }
// }