<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Paiement;
use App\Entity\User;
use App\Form\PaiementFormType;
use App\Repository\CommandeProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\PaiementRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/{_locale}/Commande_Panier", name="commande_panier_")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="panier")
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];
            $total = 0;

            foreach ($panier as $commande) {
//                $product = $produitRepository->find($id);
                $dataPanier[] = [
                    "produit" => $commande['produit']
                ];
//                $total += $product->getPrix() * $quantite;
            }

            $response = $this->render('commande/index.html.twig', [
                'produits' => $produitRepository->findAll(),
                'panier' => $dataPanier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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
     * @Route("/valider", name="valider")
     */
    public function valider(SessionInterface $session, ProduitRepository $produitRepository, CommandeProduitRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];
            $em = $this->getDoctrine()->getManager();
            $commande = new Commande();

            if (count($panier) >= 1) {

                $commande->setUser($this->getUser());
                $montant = 0;
                foreach ($panier as $product) {
                    $produit = $produitRepository->find($product['produit']->getId());
                    $montant = $montant + $product['produit']->getQuantite() * $produit->getPrix();
                    $commandeproduit = new CommandeProduit($produit, $commande, $produit->getPrix(), $produit->getPrixpublic(), $product['produit']->getQuantite());
                    $em->persist($commandeproduit);
                }
                $commande->setMontant($montant);
                $em->persist($commande);
                $em->flush();
                $session->remove("panier");
                $response = $this->redirectToRoute('commande_panier_imprimer', [
                    'commande' => $commande->getId(),
                ]);
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
     * @Route("/Suivi/", name="suivi")
     */
    public function Suivi(SessionInterface $session, CommandeRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $panier = $session->get("panier", []);

            $response = $this->render('commande/suivi.html.twig', [
                'commandes' => $repository->findBy(['user' => $this->getUser()->getId(), 'suivi' => false]),
                'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            $panier = $session->get("panier", []);

            $response = $this->render('commande/admin/suivi.html.twig', [
                'commandes' => $repository->findBy(['suivi' => false]),
//                'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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


        /* // On "fabrique" les données

         return $this->render('produit/index.html.twig', compact("dataPanier", "total"));*/
    }

    /**
     * @Route("/Historique/", name="history")
     */
    public function history(SessionInterface $session, CommandeRepository $repository)
    {
       if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            $panier = $session->get("panier", []);

            $response = $this->render('commande/admin/history.html.twig', [
                'commandes' => $repository->findBy(['suivi' => true]),
//                'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $panier = $session->get("panier", []);

            $response = $this->render('commande/history.html.twig', [
                'commandes' => $repository->findBy(['user' => $this->getUser()->getId(), 'suivi' => true]),
                'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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


        /* // On "fabrique" les données

         return $this->render('produit/index.html.twig', compact("dataPanier", "total"));*/
    }

    /**
     * @Route("/Historique_admin/", name="history_admin")
     */
    public function histo_admin(SessionInterface $session, CommandeRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            //$panier = $session->get("panier", []);

            $response = $this->render('commande/history_admin.html.twig', [
                'commandes' => $repository->findBy(['user' => $this->getUser()->getId()]),
                //'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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


        /* // On "fabrique" les données

         return $this->render('produit/index.html.twig', compact("dataPanier", "total"));*/
    }

    /**
     * @Route("/Suivi/{commande}", name="paiement")
     */
    public function paiement(Request $request, SessionInterface $session, CommandeProduitRepository $repository, Commande $commande)
    {
         if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
             if($commande->getSuivi()){
                 $this->addFlash('notice', 'Paiement déjà effectué');

                 $response = $this->redirectToRoute('commande_panier_history', [], Response::HTTP_SEE_OTHER);
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

            $paiement = new Paiement();
            $form = $this->createForm(PaiementFormType::class, $paiement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                If($paiement->getMontant() == $commande->getMontant()){
                $paiement->setUser($this->getUser());
                $paiement->setCommande($commande);
                $commande->setSuivi(true);
                $commande->setPaiement($paiement);
                $entityManager->persist($commande);
                $entityManager->persist($paiement);
                $entityManager->flush();
                $this->addFlash('notice', 'Paiement effectué avec succés');

                $response = $this->redirectToRoute('commande_panier_history', [], Response::HTTP_SEE_OTHER);
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
                else {
                    $this->addFlash('danger', 'Verifier le montant saisi');
                    $response = $this->redirectToRoute('commande_panier_paiement', ['commande' => $commande->getId()], Response::HTTP_SEE_OTHER);
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
            $panier = $session->get("panier", []);

            $response = $this->render('commande/admin/paiement.html.twig', [
                'commandeproduits' => $repository->findBy(['commande' => $commande]),
                'commande' => $commande,
                'panier' => $panier,
                'form' => $form->createView(),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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


        /* // On "fabrique" les données

         return $this->render('produit/index.html.twig', compact("dataPanier", "total"));*/
    }

    /**
     * @Route("/Details_commande/{commande}", name="Detail")
     */
    public function details(SessionInterface $session, CommandeProduitRepository $repository, Commande $commande, PaiementRepository $paiementRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT') && $commande->getUser() == $this->getUser()) {
            $panier = $session->get("panier", []);

            $response = $this->render('commande/details.html.twig', [
                'commandeproduits' => $repository->findBy(['commande' => $commande]),
                'commande' => $commande,
                'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {


            $response = $this->render('commande/admin/details.html.twig', [
                'commandeproduits' => $repository->findBy(['commande' => $commande]),
                'commande' => $commande,
                'paiement' => $paiementRepository->findOneBy(['commande' => $commande]),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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


        /* // On "fabrique" les données

         return $this->render('produit/index.html.twig', compact("dataPanier", "total"));*/
    }

    /**
     * @Route("/Suivi/{user}", name="suivvre")
     */
    public function suivre(SessionInterface $session, User $user, CommandeRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $panier = $session->get("panier", []);

            $response = $this->render('commande/suivi.html.twig', [
                'commandes' => $repository->findBy(['suivi' => false]),
                'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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


        /* // On "fabrique" les données

         return $this->render('produit/index.html.twig', compact("dataPanier", "total"));*/
    }
    /**
     * @Route("/Imprimer/{commande}", name="imprimer")
     */
    public function imprimer(Request $request, SessionInterface $session, CommandeProduitRepository $repository, Commande $commande)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {



            $panier = $session->get("panier", []);

            $response = $this->render('commande/validation.html.twig', [
                'commandeproduits' => $repository->findBy(['commande' => $commande]),
                'commande' => $commande,
                'panier' => $panier,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
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


        /* // On "fabrique" les données

         return $this->render('produit/index.html.twig', compact("dataPanier", "total"));*/
    }

}
