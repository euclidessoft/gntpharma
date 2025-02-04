<?php

namespace App\Controller;

use App\Entity\TypeSanction;
use App\Form\TypeSanctionType;
use App\Repository\TypeSanctionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/SanctionType/")
 */
class TypeSanctionController extends AbstractController
{
    /**
     * @Route("/", name="type_sanction_index", methods={"GET"})
     */
    public function index(TypeSanctionRepository $typeSanctionRepository): Response
    {
        return $this->render('type_sanction/index.html.twig', [
            'type_sanctions' => $typeSanctionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_sanction_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeSanction = new TypeSanction();
        $form = $this->createForm(TypeSanctionType::class, $typeSanction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeSanction);
            $entityManager->flush();

            return $this->redirectToRoute('type_sanction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_sanction/new.html.twig', [
            'type_sanction' => $typeSanction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_sanction_show", methods={"GET"})
     */
    public function show(TypeSanction $typeSanction): Response
    {
        return $this->render('type_sanction/show.html.twig', [
            'type_sanction' => $typeSanction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_sanction_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeSanction $typeSanction): Response
    {
        $form = $this->createForm(TypeSanctionType::class, $typeSanction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_sanction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_sanction/edit.html.twig', [
            'type_sanction' => $typeSanction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_sanction_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeSanction $typeSanction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeSanction->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeSanction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_sanction_index', [], Response::HTTP_SEE_OTHER);
    }
}
