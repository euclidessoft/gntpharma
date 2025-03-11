<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Departement")
 */
class DepartementController extends AbstractController
{
    /**
     * @Route("/", name="departement_index", methods={"GET"})
     */
    public function index(DepartementRepository $departementRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('departement/index.html.twig', [
                'departements' => $departementRepository->findAll(),
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
     * @Route("/new", name="departement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $departement = new Departement();
            $form = $this->createForm(DepartementType::class, $departement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($departement);
                $entityManager->flush();

                return $this->redirectToRoute('departement_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('departement/new.html.twig', [
                'departement' => $departement,
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
     * @Route("/{id}", name="departement_show", methods={"GET"})
     */
    public function show(Departement $departement): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('departement/show.html.twig', [
                'departement' => $departement,
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
     * @Route("/{id}/edit", name="departement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Departement $departement): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $form = $this->createForm(DepartementType::class, $departement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('departement_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('departement/edit.html.twig', [
                'departement' => $departement,
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
     * @Route("/{id}", name="departement_delete", methods={"POST"})
     */
    public function delete(Request $request, Departement $departement): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            if ($this->isCsrfTokenValid('delete' . $departement->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($departement);
                $entityManager->flush();
            }

            return $this->redirectToRoute('departement_index', [], Response::HTTP_SEE_OTHER);
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
