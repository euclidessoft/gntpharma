<?php

namespace App\Controller;

use App\Entity\TypeDecision;
use App\Form\TypeDecisionType;
use App\Repository\TypeDecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("{_locale}/Typedecision")
 */
class TypeDecisionController extends AbstractController
{
    /**
     * @Route("/", name="type_decision_index", methods={"GET"})
     */
    public function index(TypeDecisionRepository $typeDecisionRepository): Response
    {
        return $this->render('type_decision/index.html.twig', [
            'type_decisions' => $typeDecisionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_decision_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeDecision = new TypeDecision();
        $form = $this->createForm(TypeDecisionType::class, $typeDecision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeDecision);
            $entityManager->flush();

            return $this->redirectToRoute('type_decision_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_decision/new.html.twig', [
            'type_decision' => $typeDecision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_decision_show", methods={"GET"})
     */
    public function show(TypeDecision $typeDecision): Response
    {
        return $this->render('type_decision/show.html.twig', [
            'type_decision' => $typeDecision,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_decision_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeDecision $typeDecision): Response
    {
        $form = $this->createForm(TypeDecisionType::class, $typeDecision);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_decision_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_decision/edit.html.twig', [
            'type_decision' => $typeDecision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_decision_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeDecision $typeDecision): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeDecision->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeDecision);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_decision_index', [], Response::HTTP_SEE_OTHER);
    }
}
