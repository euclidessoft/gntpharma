<?php

namespace App\Controller;

use App\Entity\TypeConge;
use App\Form\TypeCongeType;
use App\Repository\TypeCongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Typeconge")
 */
class TypeCongeController extends AbstractController
{
    /**
     * @Route("/", name="type_conge_index", methods={"GET"})
     */
    public function index(TypeCongeRepository $typeCongeRepository): Response
    {
        return $this->render('type_conge/index.html.twig', [
            'type_conges' => $typeCongeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_conge_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeConge = new TypeConge();
        $form = $this->createForm(TypeCongeType::class, $typeConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeConge);
            $entityManager->flush();

            return $this->redirectToRoute('type_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_conge/new.html.twig', [
            'type_conge' => $typeConge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_conge_show", methods={"GET"})
     */
    public function show(TypeConge $typeConge): Response
    {
        return $this->render('type_conge/show.html.twig', [
            'type_conge' => $typeConge,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_conge_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeConge $typeConge): Response
    {
        $form = $this->createForm(TypeCongeType::class, $typeConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_conge/edit.html.twig', [
            'type_conge' => $typeConge,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_conge_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeConge $typeConge): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeConge->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeConge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_conge_index', [], Response::HTTP_SEE_OTHER);
    }
}
