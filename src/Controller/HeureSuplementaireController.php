<?php

namespace App\Controller;

use App\Entity\HeureSuplementaire;
use App\Form\HeureSuplementaireType;
use App\Repository\HeureSuplementaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/HeureSuplementaire")
 */
class HeureSuplementaireController extends AbstractController
{
    /**
     * @Route("/", name="heure_suplementaire_index", methods={"GET"})
     */
    public function index(HeureSuplementaireRepository $heureSuplementaireRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('heure_suplementaire/admin/index.html.twig', [
                'heure_suplementaires' => $heureSuplementaireRepository->findAll(),
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
     * @Route("/Suivi", name="heure_suivi", methods={"GET"})
     */
    public function suivi(Security $security): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $heures = $entityManager->getRepository(HeureSuplementaire::class)->findBy(['employe' => $employe]);
            return $this->render('heure_suplementaire/index.html.twig', [
                'heures' => $heures,
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
     * @Route("/new", name="heure_suplementaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $heureSuplementaire = new HeureSuplementaire();
            $form = $this->createForm(HeureSuplementaireType::class, $heureSuplementaire);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $heureSuplementaire->setCreatedAt(new \DateTime());
                $entityManager->persist($heureSuplementaire);
                $entityManager->flush();

                return $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('heure_suplementaire/admin/new.html.twig', [
                'heure_suplementaire' => $heureSuplementaire,
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
     * @Route("/{id}", name="heure_suplementaire_show", methods={"GET"})
     */
    public function show(HeureSuplementaire $heureSuplementaire): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('heure_suplementaire/admin/show.html.twig', [
                'heure_suplementaire' => $heureSuplementaire,
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
     * @Route("/{id}/edit", name="heure_suplementaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HeureSuplementaire $heureSuplementaire): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $form = $this->createForm(HeureSuplementaireType::class, $heureSuplementaire);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('heure_suplementaire/admin/edit.html.twig', [
                'heure_suplementaire' => $heureSuplementaire,
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
     * @Route("/{id}", name="heure_suplementaire_delete", methods={"POST"})
     */
    public function delete(Request $request, HeureSuplementaire $heureSuplementaire): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            if ($this->isCsrfTokenValid('delete' . $heureSuplementaire->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($heureSuplementaire);
                $entityManager->flush();
            }

            return $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
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
