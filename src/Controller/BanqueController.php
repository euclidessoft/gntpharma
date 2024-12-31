<?php

namespace App\Controller;

use App\Entity\Banque;
use App\Form\BanqueType;
use App\Repository\BanqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/banque")
 */
class BanqueController extends AbstractController
{
    /**
     * @Route("/", name="banque_index", methods={"GET"})
     */
    public function index(BanqueRepository $banqueRepository): Response
    {
        return $this->render('banque/index.html.twig', [
            'banques' => $banqueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="banque_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $banque = new Banque();
        $form = $this->createForm(BanqueType::class, $banque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($banque);
            $entityManager->flush();

            return $this->redirectToRoute('banque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banque/new.html.twig', [
            'banque' => $banque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="banque_show", methods={"GET"})
     */
    public function show(Banque $banque): Response
    {
        return $this->render('banque/show.html.twig', [
            'banque' => $banque,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="banque_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Banque $banque): Response
    {
        $form = $this->createForm(BanqueType::class, $banque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('banque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banque/edit.html.twig', [
            'banque' => $banque,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="banque_delete", methods={"POST"})
     */
    public function delete(Request $request, Banque $banque): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banque->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($banque);
            $entityManager->flush();
        }

        return $this->redirectToRoute('banque_index', [], Response::HTTP_SEE_OTHER);
    }
}
