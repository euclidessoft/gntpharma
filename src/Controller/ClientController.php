<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

/**
 * @Route("/{_locale}/Client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $client = $entityManager->getRepository(Client::class)->findAll();
        return $this->render('client/index.html.twig', [
            'client' => $client,
        ]);
    }


    /**
     * @Route("/new", name="client_new", methods={"GET","POST"} )
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder,TokenGeneratorInterface $tokenGenerator): Response
    {

        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $hashpass = $encoder->encodePassword($client, "Passer2023");

            $client->setPassword($hashpass);
            $client->setUsername($client->getNom());
            $client->setRoles(["ROLE_CLIENT"]);
            $client->setClient(true);
            $client->setFonction('Client');
            $token = $tokenGenerator->generateToken();
            $client->setResetToken($token);

            $entityManager->persist($client);
            $entityManager->flush();
            $compte = '411' . str_pad($client->getId() + 1, 4, '0', STR_PAD_LEFT);
            $client->setCompte($compte);

            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('notice', "Compte client crée avec succée");
            return $this->redirectToRoute("client_index");

        }


        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
            'client' => $client
        ]);
    }
}