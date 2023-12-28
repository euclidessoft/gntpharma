<?php

namespace App\Controller;

use App\Entity\Approvisionnement;
use App\Form\ApprovisionnementType;
use App\Repository\ApprovisionnementRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Stock")
 */
class ApprovisionnementController extends AbstractController
{
    /**
     * @Route("/", name="approvisionnement_index", methods={"GET"})
     */
    public function index(ApprovisionnementRepository $approvisionnementRepository, ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('approvisionnement/index.html.twig', [
            'approvisionnements' => $approvisionnementRepository->findAll(),
            'produits' => $produits
        ]);
    }

    /**
     * @Route("/new", name="approvisionnement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $approvisionnement = new Approvisionnement();
        $form = $this->createForm(ApprovisionnementType::class, $approvisionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($approvisionnement);
            $entityManager->flush();

            return $this->redirectToRoute('approvisionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('approvisionnement/new.html.twig', [
            'approvisionnement' => $approvisionnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="approvisionnement_show", methods={"GET"})
     */
    public function show(Approvisionnement $approvisionnement): Response
    {
        return $this->render('approvisionnement/show.html.twig', [
            'approvisionnement' => $approvisionnement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="approvisionnement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Approvisionnement $approvisionnement): Response
    {
        $form = $this->createForm(ApprovisionnementType::class, $approvisionnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('approvisionnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('approvisionnement/edit.html.twig', [
            'approvisionnement' => $approvisionnement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="approvisionnement_delete", methods={"POST"})
     */
    public function delete(Request $request, Approvisionnement $approvisionnement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$approvisionnement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($approvisionnement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('approvisionnement_index', [], Response::HTTP_SEE_OTHER);
    }
}
