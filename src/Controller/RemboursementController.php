<?php

namespace App\Controller;

use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Repository\RemboursementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/remboursement")
 */
class RemboursementController extends AbstractController
{
    /**
     * @Route("/", name="remboursement_index", methods={"GET"})
     */
    public function index(RemboursementRepository $remboursementRepository): Response
    {
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="remboursement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($remboursement);
            $entityManager->flush();

            return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('remboursement/new.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remboursement_show", methods={"GET"})
     */
    public function show(Remboursement $remboursement): Response
    {
        return $this->render('remboursement/show.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="remboursement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Remboursement $remboursement): Response
    {
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('remboursement/edit.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remboursement_delete", methods={"POST"})
     */
    public function delete(Request $request, Remboursement $remboursement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remboursement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($remboursement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
    }
}
