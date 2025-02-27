<?php

namespace App\Controller;

use App\Entity\ResultatEvaluation;
use App\Form\ResultatEvaluationType;
use App\Repository\ResultatEvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/ResultatEvaluation")
 */
class ResultatEvaluationController extends AbstractController
{
    /**
     * @Route("/", name="resultat_evaluation_index", methods={"GET"})
     */
    public function index(ResultatEvaluationRepository $resultatEvaluationRepository): Response
    {
        return $this->render('resultat_evaluation/index.html.twig', [
            'resultat_evaluations' => $resultatEvaluationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="resultat_evaluation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $resultatEvaluation = new ResultatEvaluation();
        $form = $this->createForm(ResultatEvaluationType::class, $resultatEvaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($resultatEvaluation);
            $entityManager->flush();

            return $this->redirectToRoute('resultat_evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resultat_evaluation/new.html.twig', [
            'resultat_evaluation' => $resultatEvaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resultat_evaluation_show", methods={"GET"})
     */
    public function show(ResultatEvaluation $resultatEvaluation): Response
    {
        return $this->render('resultat_evaluation/show.html.twig', [
            'resultat_evaluation' => $resultatEvaluation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="resultat_evaluation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ResultatEvaluation $resultatEvaluation): Response
    {
        $form = $this->createForm(ResultatEvaluationType::class, $resultatEvaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('resultat_evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('resultat_evaluation/edit.html.twig', [
            'resultat_evaluation' => $resultatEvaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="resultat_evaluation_delete", methods={"POST"})
     */
    public function delete(Request $request, ResultatEvaluation $resultatEvaluation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resultatEvaluation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($resultatEvaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('resultat_evaluation_index', [], Response::HTTP_SEE_OTHER);
    }
}
