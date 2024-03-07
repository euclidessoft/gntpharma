<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Paiement;
use App\Entity\User;
use App\Entity\Versement;
use App\Form\CommandeType;
use App\Form\PaiementFormType;
use App\Form\VersementType;
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
                    "produit" => $commande['produit'],
                    "promotion" => $commande['promotion']
                ];
                //    var_dump($dataPanier);
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
     * @Route("/Extranet", name="extranet")
     */
    public function extranet(SessionInterface $session, ProduitRepository $produitRepository, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

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
            $commande = new Commande();

            $form = $this->createForm(CommandeType::class, $commande);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($commande);
                $entityManager->flush();
            }


            $response = $this->render('commande/admin/index.html.twig', [
                'produits' => $produitRepository->findAll(),
                'panier' => $dataPanier,
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
                if($session->get("credit")){
                    $commande->setCredit(true);
                    $session->remove('credit');
                }
                $montant = 0;
                $reduction = 0;
                $tva = 0;
                foreach ($panier as $product) {
                    $reductionproduit = 0;
                    $produit = $produitRepository->find($product['produit']->getId());
                    if (!empty($produit->getPromotion())) {//traitement de la promotion avec reduction
                        if (!empty($produit->getPromotion()->getReduction())) {
                            $reductionproduit = $product['produit']->getQuantite() * $produit->getPrix() * $produit->getPromotion()->getReduction() / 100;

                            $reduction = $reduction + $reductionproduit;
                        }
                    }
                    if ($produit->getTva() == true) {
                        $tva = $tva + ((($product['produit']->getQuantite() * $produit->getPrix()) - $reductionproduit) * 0.1925);
                    }
                    $montant = $montant + $product['produit']->getQuantite() * $produit->getPrix();

                    $commandeproduit = new CommandeProduit($produit, $commande, $produit->getPrix(), $produit->getPrixpublic(), $product['produit']->getQuantite());
                    if (!empty($produit->getPromotion())) {

                        $commandeproduit->setPromotion($produit->getPromotion());
                    }
                    $em->persist($commandeproduit);
                }
                $montant = $montant + $tva - $reduction;
                $commande->setMontant($montant);
                $commande->setTva($tva);
                $commande->setReduction($reduction);
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
     * @Route("/valider_extranet", name="valider_extranet")
     */
    public function validerextranet(SessionInterface $session, ProduitRepository $produitRepository, CommandeProduitRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];
            $em = $this->getDoctrine()->getManager();
            $commande = new Commande();
            $commande->setAdmin($this->getUser());

            if (count($panier) >= 1) {

                $commande->setUser($this->getUser());
                if($session->get("credit")){
                    $commande->setCredit(true);
                    $session->remove('credit');
                }
                $montant = 0;
                $reduction = 0;
                $tva = 0;
                foreach ($panier as $product) {
                    $reductionproduit = 0;
                    $produit = $produitRepository->find($product['produit']->getId());
                    if (!empty($produit->getPromotion())) {//traitement de la promotion avec reduction
                        if (!empty($produit->getPromotion()->getReduction())) {
                            $reductionproduit = $product['produit']->getQuantite() * $produit->getPrix() * $produit->getPromotion()->getReduction() / 100;

                            $reduction = $reduction + $reductionproduit;
                        }
                    }
                    if ($produit->getTva() == true) {
                        $tva = $tva + ((($product['produit']->getQuantite() * $produit->getPrix()) - $reductionproduit) * 0.1925);
                    }
                    $montant = $montant + $product['produit']->getQuantite() * $produit->getPrix();

                    $commandeproduit = new CommandeProduit($produit, $commande, $produit->getPrix(), $produit->getPrixpublic(), $product['produit']->getQuantite());
                    if (!empty($produit->getPromotion())) {

                        $commandeproduit->setPromotion($produit->getPromotion());
                    }
                    $em->persist($commandeproduit);
                }
                $montant = $montant + $tva - $reduction;
                $commande->setMontant($montant);
                $commande->setTva($tva);
                $commande->setReduction($reduction);
                $em->persist($commande);
                $em->flush();
                $session->remove("panier");
                $response = $this->redirectToRoute('commande_panier_imprimer_extranet', [
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
     * @Route("/VosCommandes/", name="suivi")
     */
    public function voscommande(SessionInterface $session, CommandeRepository $repository)
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
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
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
     * @Route("/VosCommandes_extranet/", name="suivi_extranet")
     */
    public function voscommandeextranet(SessionInterface $session, CommandeRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $panier = $session->get("panier", []);

            $response = $this->render('commande/extranet.html.twig', [
                'commandes' => $repository->extranet($this->getUser()->getId()),
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
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
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
     * @Route("/Credit/", name="credit")
     */
    public function credit(SessionInterface $session, CommandeRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
//            $panier = $session->get("panier", []);

            $response = $this->render('commande/admin/credit.html.twig', [
                'commandes' => $repository->findBy(['paiement' => null, 'credit' => true, 'suivi' => true, 'payer' => false]),
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

            $response = $this->render('commande/credit.html.twig', [
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

    }
    /**
     * @Route("/ValidationCredit/", name="validation_credit")
     */
    public function validationcredit(SessionInterface $session, CommandeRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
//            $panier = $session->get("panier", []);

            $response = $this->render('commande/admin/validation.html.twig', [
                'commandes' => $repository->findBy(['suivi' => false, 'credit' => true]),
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

            $response = $this->render('commande/credit.html.twig', [
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

    }

    /**
     * @Route("/Historique_admin/", name="history_admin")
     */
    public function histo_admin(SessionInterface $session, CommandeRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
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
     * @Route("/Paiement_commande/{commande}", name="paiement_client")
     */
    public function paiementClient(SessionInterface $session, CommandeProduitRepository $repository, Commande $commande, PaiementRepository $paiementRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT') && $commande->getUser() == $this->getUser()) {
            $panier = $session->get("panier", []);

            $response = $this->render('commande/payment.html.twig', [
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

    /**
     * @Route("/ConfirmationPaiement/", name="confirm_paiement_client")
     */
    public function confirm(SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];
            $total = 0;

            foreach ($panier as $commande) {
//                $product = $produitRepository->find($id);
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                    "promotion" => $commande['promotion']
                ];
//                $total += $product->getPrix() * $quantite;
            }

            $response = $this->render('commande/confirm.html.twig', [
//                'produits' => $produitRepository->findAll(),
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
     * @Route("/ConfirmationPaiement_extranet/", name="confirm_paiement_extranet")
     */
    public function confirmextranet(SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];
            $total = 0;

            foreach ($panier as $commande) {
//                $product = $produitRepository->find($id);
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                    "promotion" => $commande['promotion']
                ];
//                $total += $product->getPrix() * $quantite;
            }

            $response = $this->render('commande/admin/confirm.html.twig', [
//                'produits' => $produitRepository->findAll(),
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
     * @Route("/ConfirmationCredit/", name="confirm_credit_client")
     */
    public function confirmcredit(SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $session->set("credit", 'credit');
            $dataPanier = [];
            $total = 0;

            foreach ($panier as $commande) {
//                $product = $produitRepository->find($id);
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                    "promotion" => $commande['promotion']
                ];
//                $total += $product->getPrix() * $quantite;
            }

            $response = $this->render('commande/confirm.html.twig', [
//                'produits' => $produitRepository->findAll(),
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
     * @Route("/ConfirmationCredit_extranet/", name="confirm_credit_client_extranet")
     */
    public function confirmcreditextranet(SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $panier = $session->get("panier", []);
            $session->set("credit", 'credit');
            $dataPanier = [];
            $total = 0;

            foreach ($panier as $commande) {
//                $product = $produitRepository->find($id);
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                    "promotion" => $commande['promotion']
                ];
//                $total += $product->getPrix() * $quantite;
            }

            $response = $this->render('commande/admin/confirm.html.twig', [
//                'produits' => $produitRepository->findAll(),
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
     * @Route("/Choix_Paiement/{commande}", name="choix_paiement")
     */
    public function paiementChoix(SessionInterface $session, CommandeProduitRepository $repository, $commande, PaiementRepository $paiementRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $panier = $session->get("panier", []);
            $session->remove('credit');


            $response = $this->render('commande/traitement.html.twig', [
                'commandeproduits' => $repository->findBy(['commande' => $commande]),
                'commande' => $this->getDoctrine()->getRepository(Commande::class)->find($commande),
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
     * @Route("/Choix_Paiement_extranet/{commande}", name="choix_paiement_extranet")
     */
    public function paiementChoixextranet(SessionInterface $session, CommandeProduitRepository $repository, $commande, PaiementRepository $paiementRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $panier = $session->get("panier", []);
            $session->remove('credit');


            $response = $this->render('commande/admin/traitement.html.twig', [
                'commandeproduits' => $repository->findBy(['commande' => $commande]),
                'commande' => $this->getDoctrine()->getRepository(Commande::class)->find($commande),
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
     * @Route("/Suivi/{commande}", name="paiement")
     */
    public function paiement(Request $request, SessionInterface $session, CommandeProduitRepository $repository, Commande $commande)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            if ($commande->getSuivi()) {
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
                if (($paiement->getMontant() == $commande->getMontant()) || $commande->getCredit()) {
                    $paiement->setUser($this->getUser());
                    $paiement->setCommande($commande);
                    $commande->setSuivi(true);
                    $commande->setPayer(true);
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
                } else {
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
     * @Route("/SuiviCredit/{commande}", name="paiement_credit")
     */
    public function paiementcredit(Request $request, SessionInterface $session, CommandeProduitRepository $repository, Commande $commande)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
//            if ($commande->getSuivi()) {
//                $this->addFlash('notice', 'Paiement déjà effectué');
//
//                $response = $this->redirectToRoute('commande_panier_history', [], Response::HTTP_SEE_OTHER);
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//
//            }

            $versement = new Versement();
            $form = $this->createForm(VersementType::class, $versement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                if ($versement->getMontant() <= ($commande->getMontant() - $commande->getVersement())) {
                    $commande->setVersement($commande->getVersement() + $versement->getMontant());
                    if($commande->getVersement() == $commande->getMontant()) $commande->setPayer(true);
                    $versement->setUser($this->getUser());
                    $versement->setCommande($commande);
//                    $commande->setSuivi(true);
//                    $commande->setPaiement($versement);
//                    $entityManager->persist($commande);
                    $entityManager->persist($versement);
                    $entityManager->flush();
                    $this->addFlash('notice', 'Versement effectué avec succés');

                    $response = $this->redirectToRoute('commande_panier_paiement_credit', ['commande' => $commande->getId()], Response::HTTP_SEE_OTHER);
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
                    $this->addFlash('danger', 'Verifier le montant saisi');
                    $response = $this->redirectToRoute('commande_panier_paiement_credit', ['commande' => $commande->getId()], Response::HTTP_SEE_OTHER);
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

            $response = $this->render('commande/admin/paiementcredit.html.twig', [
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
     * @Route("/ValiderCredit/{commande}", name="valider_credit")
     */
    public function validercredit(Request $request, SessionInterface $session, CommandeProduitRepository $repository, Commande $commande)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            if ($commande->getSuivi()) {
                $this->addFlash('notice', 'Paiement déjà effectué');

                $response = $this->redirectToRoute('commande_panier_credit', [], Response::HTTP_SEE_OTHER);
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
            $em = $this->getDoctrine()->getManager();
            $commande->setSuivi(true);
            $em->persist($commande);
            $em->flush();
            $response = $this->redirectToRoute('commande_panier_credit', [], Response::HTTP_SEE_OTHER);
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
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {


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
     * @Route("/Pint_Details_commande/{commande}", name="print_Detail")
     */
    public function printdetails(SessionInterface $session, CommandeProduitRepository $repository, Commande $commande, PaiementRepository $paiementRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT') && $commande->getUser() == $this->getUser()) {
            $panier = $session->get("panier", []);

            $response = $this->render('commande/details_print.html.twig', [
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
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {


            $response = $this->render('commande/admin/details_print.html.twig', [
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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

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

            $response = $this->render('commande/confirm_print.html.twig', [
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

 /**
     * @Route("/Imprimer_extranet/{commande}", name="imprimer_extranet")
     */
    public function imprimerextranet(Request $request, SessionInterface $session, CommandeProduitRepository $repository, Commande $commande)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {


            $panier = $session->get("panier", []);

            $response = $this->render('commande/admin/confirm_print.html.twig', [
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
