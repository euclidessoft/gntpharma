<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livrer;
use App\Form\LivrerType;
use App\Repository\CommandeProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\LivrerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/livraison", name="livraison_")
 */
class LivrerController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(LivrerRepository $livrerRepository, CommandeRepository $repository): Response
    {
        return $this->render('livrer/index.html.twig', [
            'livrers' => $livrerRepository->findAll(),
            'commandes' => $repository->findBy(['suivi' => true]),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $livrer = new Livrer();
        $form = $this->createForm(LivrerType::class, $livrer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livrer);
            $entityManager->flush();

            return $this->redirectToRoute('livrer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livrer/new.html.twig', [
            'livrer' => $livrer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Commande $commande, CommandeProduitRepository $repository): Response
    {// traitement livraison
        return $this->render('livrer/show.html.twig', [
            'commandes' => $repository->findBy(['commande' => $commande]),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Livrer $livrer): Response
    {
        $form = $this->createForm(LivrerType::class, $livrer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livrer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livrer/edit.html.twig', [
            'livrer' => $livrer,
            'form' => $form->createView(),
        ]);
    }

    /**`
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Livrer $livrer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livrer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livrer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livrer_index', [], Response::HTTP_SEE_OTHER);
    }
}
