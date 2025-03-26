<?php

namespace App\Controller;

use App\Entity\TypeContrat;
use App\Form\TypeContratType;
use App\Repository\TypeContratRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/TypeContrat")
 */
class TypeContratController extends AbstractController
{
    /**
     * @Route("/", name="type_contrat_index", methods={"GET"})
     */
    public function index(TypeContratRepository $typeContratRepository): Response
    {
        return $this->render('type_contrat/index.html.twig', [
            'type_contrats' => $typeContratRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_contrat_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeContrat = new TypeContrat();
        $form = $this->createForm(TypeContratType::class, $typeContrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeContrat);
            $entityManager->flush();

            return $this->redirectToRoute('type_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_contrat/new.html.twig', [
            'type_contrat' => $typeContrat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_contrat_show", methods={"GET"})
     */
    public function show(TypeContrat $typeContrat): Response
    {
        return $this->render('type_contrat/show.html.twig', [
            'type_contrat' => $typeContrat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_contrat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeContrat $typeContrat): Response
    {
        $form = $this->createForm(TypeContratType::class, $typeContrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_contrat/edit.html.twig', [
            'type_contrat' => $typeContrat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_contrat_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeContrat $typeContrat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeContrat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeContrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
}
