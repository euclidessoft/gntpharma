<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\FournisseurProduit;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("{_locale}/fournisseur")
 */
class FournisseurController extends AbstractController
{
    /**
     * @Route("/", name="fournisseur_index", methods={"GET"})
     */
    public function index(FournisseurRepository $fournisseurRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('fournisseur/index.html.twig', [
                'fournisseurs' => $fournisseurRepository->findAll(),
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
     * @Route("/new", name="fournisseur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $fournisseur = new Fournisseur();
            $form = $this->createForm(FournisseurType::class, $fournisseur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($fournisseur);
                $entityManager->flush();

                $compte = '401' . str_pad($fournisseur->getId(), 4, '0', STR_PAD_LEFT);
                $fournisseur->setCompte($compte);
                $entityManager->persist($fournisseur);
                $entityManager->flush();

                return $this->redirectToRoute('fournisseur_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('fournisseur/new.html.twig', [
                'fournisseur' => $fournisseur,
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
     * @Route("/{id}", name="fournisseur_show", methods={"GET"})
     */
    public function show(Fournisseur $fournisseur): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('fournisseur/show.html.twig', [
                'fournisseur' => $fournisseur,
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
     * @Route("/{id}/edit", name="fournisseur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fournisseur $fournisseur): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $form = $this->createForm(FournisseurType::class, $fournisseur);
            $form->remove('compte');
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('fournisseur_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('fournisseur/edit.html.twig', [
                'fournisseur' => $fournisseur,
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
     * @Route("/{id}", name="fournisseur_delete", methods={"POST"})
     */
    public function delete(Request $request, Fournisseur $fournisseur): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            if ($this->isCsrfTokenValid('delete' . $fournisseur->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($fournisseur);
                $entityManager->flush();
            }

            return $this->redirectToRoute('fournisseur_index', [], Response::HTTP_SEE_OTHER);
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
