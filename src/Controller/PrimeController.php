<?php

namespace App\Controller;

use App\Entity\Prime;
use App\Form\PrimeType;
use App\Repository\PrimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Prime")
 */
class PrimeController extends AbstractController
{
    /**
     * @Route("/", name="prime_index", methods={"GET"})
     */
    public function index(PrimeRepository $primeRepository): Response
    {
        return $this->render('prime/index.html.twig', [
            'primes' => $primeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="prime_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $prime = new Prime();
        $form = $this->createForm(PrimeType::class, $prime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $prime->setCreatedAt(new  \DateTime());
            $entityManager->persist($prime);
            $entityManager->flush();

            return $this->redirectToRoute('prime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prime/new.html.twig', [
            'prime' => $prime,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prime_show", methods={"GET"})
     */
    public function show(Prime $prime): Response
    {
        return $this->render('prime/show.html.twig', [
            'prime' => $prime,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prime_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Prime $prime): Response
    {
        $form = $this->createForm(PrimeType::class, $prime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prime/edit.html.twig', [
            'prime' => $prime,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prime_delete", methods={"POST"})
     */
    public function delete(Request $request, Prime $prime): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prime->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($prime);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prime_index', [], Response::HTTP_SEE_OTHER);
    }
}
