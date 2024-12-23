<?php

namespace App\Controller;

use App\Entity\Financement;
use App\Form\FinancementType;
use App\Repository\FinancementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/financement")
 */
class FinancementController extends AbstractController
{
    /**
     * @Route("/", name="financement_index", methods={"GET"})
     */
    public function index(FinancementRepository $financementRepository): Response
    {
        return $this->render('financement/index.html.twig', [
            'financements' => $financementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="financement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $financement = new Financement();
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($financement);
            $entityManager->flush();

            return $this->redirectToRoute('financement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('financement/new.html.twig', [
            'financement' => $financement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="financement_show", methods={"GET"})
     */
    public function show(Financement $financement): Response
    {
        return $this->render('financement/show.html.twig', [
            'financement' => $financement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="financement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Financement $financement): Response
    {
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('financement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('financement/edit.html.twig', [
            'financement' => $financement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="financement_delete", methods={"POST"})
     */
    public function delete(Request $request, Financement $financement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$financement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($financement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('financement_index', [], Response::HTTP_SEE_OTHER);
    }
}
