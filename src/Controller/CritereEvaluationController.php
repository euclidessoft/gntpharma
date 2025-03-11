<?php

namespace App\Controller;

use App\Entity\CritereEvaluation;
use App\Form\CritereEvaluationType;
use App\Repository\CritereEvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/CritereEvaluation")
 */
class CritereEvaluationController extends AbstractController
{
    /**
     * @Route("/", name="critere_evaluation_index", methods={"GET"})
     */
    public function index(CritereEvaluationRepository $critereEvaluationRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('critere_evaluation/index.html.twig', [
                'critere_evaluations' => $critereEvaluationRepository->findAll(),
            ]);
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    /**
     * @Route("/new", name="critere_evaluation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $critereEvaluation = new CritereEvaluation();
            $form = $this->createForm(CritereEvaluationType::class, $critereEvaluation);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($critereEvaluation);
                $entityManager->flush();

                return $this->redirectToRoute('critere_evaluation_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('critere_evaluation/new.html.twig', [
                'critere_evaluation' => $critereEvaluation,
                'form' => $form->createView(),
            ]);
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    /**
     * @Route("/{id}", name="critere_evaluation_show", methods={"GET"})
     */
    public function show(CritereEvaluation $critereEvaluation): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('critere_evaluation/show.html.twig', [
                'critere_evaluation' => $critereEvaluation,
            ]);
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    /**
     * @Route("/{id}/edit", name="critere_evaluation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CritereEvaluation $critereEvaluation): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $form = $this->createForm(CritereEvaluationType::class, $critereEvaluation);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('critere_evaluation_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('critere_evaluation/edit.html.twig', [
                'critere_evaluation' => $critereEvaluation,
                'form' => $form->createView(),
            ]);
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    /**
     * @Route("/{id}", name="critere_evaluation_delete", methods={"POST"})
     */
    public function delete(Request $request, CritereEvaluation $critereEvaluation): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            if ($this->isCsrfTokenValid('delete' . $critereEvaluation->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($critereEvaluation);
                $entityManager->flush();
            }

            return $this->redirectToRoute('critere_evaluation_index', [], Response::HTTP_SEE_OTHER);

        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }
}
