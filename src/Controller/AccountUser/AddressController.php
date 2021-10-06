<?php

namespace App\Controller\AccountUser;

use App\Entity\User;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManager;
use App\Outils\Cart\CartService;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/compte/addresse")
 */
class AddressController extends AbstractController
{

    // public function __construct(EntityManager $entityManager)
    // {
    //     $this->entityManager = $entityManager;
    // }
    /**
     * @Route("/", name="address_index", methods={"GET"})
     */
    public function index(AddressRepository $addressRepository): Response
    {
        /* récupérer les adresses de la bdd en fonction de l'user*/

        $value = $this->getUser();

        return $this->render('address/index.html.twig', [
            'addresses' => $addressRepository->findByUser($value),
        ]);
        // return $this->render('address/index.html.twig', [
        //     'addresses' => $addressRepository->findAll(),
        // ]);
    }

    /**
     * @Route("/new", name="address_new", methods={"GET","POST"})
     */
    public function new(Request $request, CartService $cart): Response
    {

        $user = $this->getUser();

        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($user); //le champs user de la table address prend comme valeur le user_id de l'utilistaeur qui crée ce post

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            //test

            $entityManager->persist($user);
            //fin test
            $entityManager->flush();

            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('address_index');
            }
        }

        return $this->render('address/new.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="address_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Address $address, $id): Response
    {
        //a revoir pr la secu de ladresse a modif

        // $entityanager = $this->getDoctrine()->getManager();
        // $addressToEdit = $this->$entityanager->getRepository(Address::class)->find($id);

        // if (!$addressToEdit || $addressToEdit->getUser() != $this->getUser()) {
        //     return $this->redirectToRoute('address_index');
        // }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('address_index');
        }

        return $this->render('address/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="address_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Address $address): Response
    {
        if ($this->isCsrfTokenValid('delete' . $address->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('address_index');
    }
}