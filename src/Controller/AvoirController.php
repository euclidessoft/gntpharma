<?php

namespace App\Controller;

use App\Entity\Avoir;
use App\Entity\AvoirReste;
use App\Entity\Commande;
use App\Entity\Retour;
use App\Entity\Livrer;
use App\Entity\LivrerReste;
use App\Entity\Reclamation;
use App\Entity\RetourProduit;
use App\Form\AvoirType;
use App\Repository\AvoirRepository;
use App\Repository\AvoirResteRepository;
use App\Repository\CommandeProduitRepository;
use App\Repository\LivrerProduitRepository;
use App\Repository\LivrerRepository;
use App\Repository\LivrerResteRepository;
use App\Repository\ProduitRepository;
use App\Repository\ReclamationRepository;
use App\Repository\RetourProduitRepository;
use App\Repository\RetourRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Avoir")
 */
class AvoirController extends AbstractController
{
    /**
     * @Route("/", name="avoir_index", methods={"GET"})
     */
    public function index(AvoirRepository $avoirRepository, SessionInterface $session): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/admin/index.html.twig', [
                'avoirs' => $avoirRepository->findAll(),

            ]);
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            return $this->render('avoir/index.html.twig', [
                'avoirs' => $avoirRepository->findby(['client' => $this->getUser()]),
                'panier' => $panier = $session->get("panier", []),
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
     * @Route("/Choix_Avoir", name="avoir_choix", methods={"GET"})
     */
    public function choix(AvoirRepository $avoirRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/admin/choix.html.twig');
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
     * @Route("/Reste", name="avoir_reste", methods={"GET"})
     */
    public function reste(LivrerRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/admin/reste.html.twig', [
                'livrers' => $repository->findBy(['reste' => true]),
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
     * @Route("/Reclamation", name="avoir_reclamation", methods={"GET"})
     */
    public function reclamation(ReclamationRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/admin/reclamation.html.twig', [
                'livrers' => $repository->findBy(['cloture' => null]),
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
     * @Route("/new/reste/{id}", name="avoir_new_reste", methods={"GET","POST"})
     */
    public function newreste(Commande $commande, LivrerResteRepository $livrerResteRepository, CommandeProduitRepository $comprodrepository, ProduitRepository $repository, SessionInterface $session): Response
    {// traitement livraison
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $session->remove("livraison");
            $commandeproduits = $livrerResteRepository->findBy(['commande' => $commande]);
            $listcommande = [];
            foreach ($commandeproduits as $commandeproduit) {
                $stock = $repository->find($commandeproduit->getProduit()->getId())->getStock();
                $commandeproduit->setStock($stock);
                $listcommande[] = $commandeproduit;
            }
            $session->set("traitement", []);
            $response = $this->render('avoir/admin/new_reste.html.twig', [
                'commandes' => $listcommande,
                'commandereference' => $commande,
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
     * @Route("/new/reclamation/{id}/", name="avoir_new_reclamation", methods={"GET","POST"})
     */
    public function newreclamation(Reclamation $reclamation, Request $request, LivrerResteRepository $livrerResteRepository, ProduitRepository $repository, LivrerProduitRepository $livrerProduitRepository, SessionInterface $session): Response
    {// traitement livraison
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $commande = $reclamation->getCommande();
            $em = $this->getDoctrine()->getManager();
            $avoir = new Avoir($commande->getUser(),$this->getUser(), $commande);
            $avoir->setReclamation($reclamation);
            $form = $this->createForm(AvoirType::class, $avoir);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $reclamation->setCloture(new \Datetime());
                $reclamation->setUsercloture($this->getUser());
                $em->persist($avoir);
                $em->persist($reclamation);
                $em->flush();


                return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
            }

//            return $this->render('avoir/admin/edit.html.twig', [
//                'avoir' => $avoir,
//                'form' => $form->createView(),
//            ]);
            $commandeproduits = $livrerResteRepository->findBy(['commande' => $commande]);
            $listcommande = [];
            foreach ($commandeproduits as $commandeproduit) {
                $stock = $repository->find($commandeproduit->getProduit()->getId())->getStock();
                $commandeproduit->setStock($stock);
                $listcommande[] = $commandeproduit;
            }
            $session->set("traitement", []);
            $response = $this->render('avoir/admin/new_reclamation.html.twig', [
                'commandes' => $listcommande,
                'commandereference' => $commande,
                'reclamation' => $reclamation,
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
     * @Route("/validerNewreste/", name="avoir_valider_reste")
     */
    public function validernewreste(Request $request, LivrerResteRepository $livrerResteRepository)
    {
        // On récupère le panier actuel

        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $em = $this->getDoctrine()->getManager();
            $produits = explode(";", $request->get('prod'));// recuperation des produit
            $com = $request->get('commande');// recuperation de la commamde
            $commande = $em->getRepository(Commande::class)->find($com);
            $avoir = $em->getRepository(Avoir::class)->findOneBy(['commande' => $commande]);

            if ($avoir == null) {
                $avoir = new Avoir($commande->getUser(), $this->getUser(), $commande);
            }

            $montantavoir = 0;
            $livraison = 0;
            foreach ($produits as $prod) {
                if ($prod != 0) {
                    $reste = $livrerResteRepository->findOneBy(['commande' => $com, 'produit' => $prod]);
                    $livraison = $reste->getLivrer()->getId();
                    $avoirresete = new AvoirReste($reste->getLivrer(), $commande, $reste->getProduit(), $reste->getQuantite(), $reste->getQuantitelivre(), $reste->getClient(), $this->getUser(), $avoir);
                    $montantavoir = $montantavoir + (($reste->getQuantite() - $reste->getQuantitelivre()) * $reste->getSession());
                    $em->persist($avoirresete);
                    $em->remove($reste);
                }
            }
            $avoir->setMontant($avoir->getMontant() + $montantavoir);
            $em->persist($avoir);
            $em->flush();
            // verification epuisement reste a livrer
            $livrerreste = $livrerResteRepository->findOneBy(['livrer' => $livraison]);
            if (empty($livrerreste)) {// suppression reste a livrer
                $livrer = $em->getRepository(Livrer::class)->find($livraison);
                $livrer->setReste(false);
                $em->persist($livrer);
                $em->flush();
            }
            //fin verif
            $this->addFlash('notice', 'Avoir créé avec succès');
            $res['id'] = 'ok';


            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }



//    public function new(Request $request, LivrerReste $reste): Response
//    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
//            $avoir = new Avoir();
//            $form = $this->createForm(AvoirType::class, $avoir);
//            $form->handleRequest($request);
//
//            if ($form->isSubmitted() && $form->isValid()) {
//                $entityManager = $this->getDoctrine()->getManager();
//                $avoir->setAdmin($this->getUser());
//                $avoir->setClient($this->getUser());
//                $entityManager->persist($avoir);
//                $entityManager->flush();
//
//                return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
//            }
//
//            return $this->render('avoir/admin/new.html.twig', [
//                'avoir' => $avoir,
//                'form' => $form->createView(),
//            ]);
//        } else {
//            $response = $this->redirectToRoute('security_logout');
//            $response->setSharedMaxAge(0);
//            $response->headers->addCacheControlDirective('no-cache', true);
//            $response->headers->addCacheControlDirective('no-store', true);
//            $response->headers->addCacheControlDirective('must-revalidate', true);
//            $response->setCache([
//                'max_age' => 0,
//                'private' => true,
//            ]);
//            return $response;
//        }
//    }

    /**
     * @Route("/{id}", name="avoir_show", methods={"GET"})
     */
    public function show(Avoir $avoir, AvoirResteRepository $avoirResteRepository, SessionInterface $session): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/admin/show.html.twig', [
                'avoir' => $avoir,
                'details' => $avoirResteRepository->findBy(['avoir' => $avoir])
            ]);
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            return $this->render('avoir/show.html.twig', [
                'avoir' => $avoir,
                'details' => $avoirResteRepository->findBy(['avoir' => $avoir]),
                'panier' => $session->get('panier', []),
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
     * @Route("/Print/{id}", name="avoir_show_print", methods={"GET"})
     */
    public function showprint(Avoir $avoir, AvoirResteRepository $avoirResteRepository, SessionInterface $session): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/admin/show_print.html.twig', [
                'avoir' => $avoir,
                'details' => $avoirResteRepository->findBy(['avoir' => $avoir])
            ]);
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            return $this->render('avoir/show_print.html.twig', [
                'avoir' => $avoir,
                'details' => $avoirResteRepository->findBy(['avoir' => $avoir]),
                'panier' => $session->get('panier', []),
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
     * @Route("/{id}/edit", name="avoir_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Avoir $avoir): Response
    {
        $form = $this->createForm(AvoirType::class, $avoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avoir/admin/edit.html.twig', [
            'avoir' => $avoir,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avoir_delete", methods={"POST"})
     */
    public function delete(Request $request, Avoir $avoir): Response
    {
        if ($this->isCsrfTokenValid('delete' . $avoir->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avoir);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
    }
}
