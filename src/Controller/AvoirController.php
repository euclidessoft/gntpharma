<?php

namespace App\Controller;

use App\Entity\Avoir;
use App\Form\AvoirType;
use App\Repository\AvoirRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/avoir")
 */
class AvoirController extends AbstractController
{
    /**
     * @Route("/", name="avoir_index", methods={"GET"})
     */
    public function index(AvoirRepository $avoirRepository): Response
    {
        return $this->render('avoir/index.html.twig', [
            'avoirs' => $avoirRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="avoir_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $avoir = new Avoir();
        $form = $this->createForm(AvoirType::class, $avoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $avoir->setAdmin($this->getUser());
            $avoir->setClient($this->getUser());
            $entityManager->persist($avoir);
            $entityManager->flush();

            return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avoir/new.html.twig', [
            'avoir' => $avoir,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avoir_show", methods={"GET"})
     */
    public function show(Avoir $avoir): Response
    {
        return $this->render('avoir/show.html.twig', [
            'avoir' => $avoir,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avoir_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Avoir $avoir): Response
    {
        $form = $this->createForm(AvoirType::class, $avoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avoir/edit.html.twig', [
            'avoir' => $avoir,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avoir_delete", methods={"POST"})
     */
    public function delete(Request $request, Avoir $avoir): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avoir->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avoir);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
    }
}
