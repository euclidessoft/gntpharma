<?php

namespace App\Controller;

use App\Entity\PrimePerformance;
use App\Form\PrimePerformanceType;
use App\Repository\PrimePerformanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/prime/performance")
 */
class PrimePerformanceController extends AbstractController
{
    /**
     * @Route("/", name="prime_performance_index", methods={"GET"})
     */
    public function index(PrimePerformanceRepository $primePerformanceRepository): Response
    {
        return $this->render('prime_performance/index.html.twig', [
            'prime_performances' => $primePerformanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="prime_performance_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $primePerformance = new PrimePerformance();
        $form = $this->createForm(PrimePerformanceType::class, $primePerformance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($primePerformance);
            $entityManager->flush();

            return $this->redirectToRoute('prime_performance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prime_performance/new.html.twig', [
            'prime_performance' => $primePerformance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prime_performance_show", methods={"GET"})
     */
    public function show(PrimePerformance $primePerformance): Response
    {
        return $this->render('prime_performance/show.html.twig', [
            'prime_performance' => $primePerformance,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="prime_performance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PrimePerformance $primePerformance): Response
    {
        $form = $this->createForm(PrimePerformanceType::class, $primePerformance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prime_performance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prime_performance/edit.html.twig', [
            'prime_performance' => $primePerformance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="prime_performance_delete", methods={"POST"})
     */
    public function delete(Request $request, PrimePerformance $primePerformance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$primePerformance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($primePerformance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('prime_performance_index', [], Response::HTTP_SEE_OTHER);
    }
}
