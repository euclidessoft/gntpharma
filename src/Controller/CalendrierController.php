<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Form\CalendrierType;
use App\Repository\CalendrierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Calendrier")
 */
class CalendrierController extends AbstractController
{
    /**
     * @Route("/", name="calendrier_index", methods={"GET"})
     */
    public function index(CalendrierRepository $calendrierRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('calendrier/index.html.twig', [
                'calendriers' => $calendrierRepository->findAll(),
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
     * @Route("/new", name="calendrier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $calendrier = new Calendrier();
            $form = $this->createForm(CalendrierType::class, $calendrier);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($calendrier);
                $entityManager->flush();

                return $this->redirectToRoute('calendrier_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('calendrier/new.html.twig', [
                'calendrier' => $calendrier,
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
     * @Route("/{id}", name="calendrier_show", methods={"GET"})
     */
    public function show(Calendrier $calendrier): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('calendrier/show.html.twig', [
                'calendrier' => $calendrier,
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
     * @Route("/{id}/edit", name="calendrier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Calendrier $calendrier): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $form = $this->createForm(CalendrierType::class, $calendrier);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('calendrier_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('calendrier/edit.html.twig', [
                'calendrier' => $calendrier,
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
     * @Route("/{id}", name="calendrier_delete", methods={"POST"})
     */
    public function delete(Request $request, Calendrier $calendrier): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            if ($this->isCsrfTokenValid('delete' . $calendrier->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($calendrier);
                $entityManager->flush();
            }

            return $this->redirectToRoute('calendrier_index', [], Response::HTTP_SEE_OTHER);
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
