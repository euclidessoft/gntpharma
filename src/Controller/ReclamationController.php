<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;
use App\Form\ReclamationType;
use App\Repository\LivrerProduitRepository;
use App\Repository\LivrerRepository;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Commande_Reclamation")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/{user}", name="reclamation_index", methods={"GET"})
     */
    public function index(SessionInterface $session, ReclamationRepository $reclamationRepository, User $user): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            return $this->render('reclamation/admin/index.html.twig', [
                'reclamations' => $reclamationRepository->findBy(['user' => $user]),
            ]);
        }else if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->render('reclamation/admin/index.html.twig', [
                'reclamations' => $reclamationRepository->findAll(),
            ]);

        }else {
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
     * @Route("/new", name="reclamation_new", methods={"GET","POST"})
     */
    public function new(SessionInterface $session, Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach ($panier as $commande) {
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
            }
            $reclamation = new Reclamation();
            $form = $this->createForm(ReclamationType::class, $reclamation, ['id' => $this->getUser()->getId()]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($reclamation);
                $entityManager->flush();

                return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('reclamation/new.html.twig', [
                'reclamation' => $reclamation,
                'form' => $form->createView(),
                'panier' => $dataPanier,
            ]);
        } else {
            $response = $this->redirectToRoute('security_login');
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
     * @Route("/{id}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation, LivrerProduitRepository $livrerProduitRepository, SessionInterface $session): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {



            $commandeproduits = $livrerProduitRepository->findBy(['commande' => $reclamation->getCommande()]);
            return $this->render('reclamation/admin/show.html.twig', [
                'reclamation' => $reclamation,
                'commandes' => $commandeproduits,
            ]);
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach ($panier as $commande) {
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
            }



            $commandeproduits = $livrerProduitRepository->findBy(['commande' => $reclamation->getCommande()]);
            return $this->render('reclamation/show.html.twig', [
                'reclamation' => $reclamation,
                'commandes' => $commandeproduits,
                'panier' => $dataPanier,
            ]);
        } else {
            $response = $this->redirectToRoute('security_login');
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
     * @Route("/{id}/edit", name="reclamation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
