<?php

namespace App\Controller;

use App\Entity\HeureSuplementaire;
use App\Form\HeureSuplementaireType;
use App\Repository\HeureSuplementaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/HeureSuplementaire")
 */
class HeureSuplementaireController extends AbstractController
{
    /**
     * @Route("/", name="heure_suplementaire_index", methods={"GET"})
     */
    public function index(HeureSuplementaireRepository $heureSuplementaireRepository): Response
    {
        return $this->render('heure_suplementaire/index.html.twig', [
            'heure_suplementaires' => $heureSuplementaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="heure_suplementaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $heureSuplementaire = new HeureSuplementaire();
        $form = $this->createForm(HeureSuplementaireType::class, $heureSuplementaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $heureSuplementaire->setCreatedAt(new \DateTime());
            $entityManager->persist($heureSuplementaire);
            $entityManager->flush();

            return $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('heure_suplementaire/new.html.twig', [
            'heure_suplementaire' => $heureSuplementaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heure_suplementaire_show", methods={"GET"})
     */
    public function show(HeureSuplementaire $heureSuplementaire): Response
    {
        return $this->render('heure_suplementaire/show.html.twig', [
            'heure_suplementaire' => $heureSuplementaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="heure_suplementaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HeureSuplementaire $heureSuplementaire): Response
    {
        $form = $this->createForm(HeureSuplementaireType::class, $heureSuplementaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('heure_suplementaire/edit.html.twig', [
            'heure_suplementaire' => $heureSuplementaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="heure_suplementaire_delete", methods={"POST"})
     */
    public function delete(Request $request, HeureSuplementaire $heureSuplementaire): Response
    {
        if ($this->isCsrfTokenValid('delete' . $heureSuplementaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($heureSuplementaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
