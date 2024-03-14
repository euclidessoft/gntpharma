<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Candidature;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Retour;
use App\Entity\RetourProduit;
use App\Form\CandidatureType;
use App\Repository\CommandeProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\ImageRepository;
use App\Repository\ProduitRepository;
use App\Repository\PromotionRepository;
use App\Repository\RetourProduitRepository;
use App\Repository\RetourRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Stock" , name="stock_")
 */
class StockController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function stock(StockRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
            return $this->render('stock/stock.html.twig', [
                'stock' => $repository->stock(),
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
     * @Route("/Surveiller", name="surveiller", methods={"GET"})
     */
    public function surveiller(ProduitRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_BACK')) {
            return $this->render('stock/surveiller.html.twig', [
                'produits' => $repository->surveil(),
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
     * @Route("/Rupture", name="rupture", methods={"GET"})
     */
    public function rupture(ProduitRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_BACK')) {
            return $this->render('stock/rupture.html.twig', [
                'produits' => $repository->findBy(['stock' => 0]),
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
     * @Route("/Peremption", name="peremption", methods={"GET"})
     */
    public function peremption(StockRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_BACK')) {
            return $this->render('stock/peremption.html.twig', [
                'stocks' => $repository->peremption(),
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
     * @Route("/Retour/", name="retour", methods={"GET"})
     */
    public function retour(CommandeRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
            return $this->render('stock/retour.html.twig', [
                'commandes' => $repository->findBy(['livrer' => true]),
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
     * @Route("/Retour_index/", name="retour_index", methods={"GET"})
     */
    public function retourindex(RetourRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
            return $this->render('stock/retour_index.html.twig', [
                'retours' => $repository->findAll(),
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
     * @Route("/Create_Retour_Show/{id}", name="retour_show", methods={"GET"})
     */
    public function retour_show(Commande $commande, CommandeProduitRepository $repository, SessionInterface $session): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
            return $this->render('stock/retour_show.html.twig', [
                'commande' => $commande,
                'commandeproduits' => $repository->findBy(['commande' => $commande]),
                'retour' => $session->get("retour", []),
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
     * @Route("/Retour_history_show/{id}", name="retour_history_show", methods={"GET"})
     */
    public function retourhistoryshow(Retour $retour, RetourProduitRepository $repository, SessionInterface $session): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
            return $this->render('stock/history_show.html.twig', [
                'retour' => $retour,
                'retourproduits' => $repository->findBy(['retour' => $retour]),
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
     * @Route("/Retour_valider/", name="retour_valider", methods={"POST"})
     */
    public function retour_valider(Request $request, SessionInterface $session): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {

            $com = $request->get('commande');
            $em = $this->getDoctrine()->getManager();
            $commande = $em->getRepository(Commande::class)->find($com);
            $produits = $session->get('retour', []);
            $retour = new Retour();
            $em->persist($retour);
            $retour->setCommande($commande);
            foreach ($produits as $prod){
                $produit = $em->getRepository(Produit::class)->find($prod['id']);
                $retourproduit = new RetourProduit();
                $retourproduit->setProduit($produit);
                $retourproduit->setRetour($retour);
                $retourproduit->setCommande($commande);
                $retourproduit->setMotif($prod['motif']);
                $retourproduit->setQuantite($prod['quantite']);
                $em->persist($retourproduit);
                $em->flush();

            }
            $em->flush();
            $this->addFlash('notice', 'Retour enregiste avec succes');
            $session->remove('retour');

            $res['id'] = 'ok';
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/{id}", name="produit_show", methods={"GET"})
     */
    public function produit(Produit $produit, StockRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {

            $response = $this->render('stock/produit_show.html.twig', [
                'stock' => $repository->findBy(['produit' => $produit]),
                'produit' => $produit,
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
     * @Route("/print/{id}", name="produit_show_print", methods={"GET"})
     */
    public function produitprint(Produit $produit, StockRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {

            $response = $this->render('stock/produit_show_print.html.twig', [
                'stock' => $repository->findBy(['produit' => $produit]),
                'produit' => $produit,
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
     * @Route("/add/", name="retour_add")
     */
    public function add(Request $request, ProduitRepository $produitRepository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $retour = $session->get("retour", []);
        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $motif = $request->get('motif');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde

            foreach ($retour as $key => $item) {
                if ($item['id'] == $id) {
                    $res['id'] = 'Un produit avec les même reference a été ajouté';
                    goto suite;
                }
            }
                $produit = $produitRepository->find($id);
                $res['idp'] = 'ok';
                $res['id'] = $id;
                $res['ref'] = $produit->getReference();
                $res['designation'] = $produit->getDesigantion();
                $res['quantite'] = $quantite;//$produit->getQuantite();
                $res['motif'] = $motif;//$produit->getQuantite();
                $retour[]= $res;


            // On sauvegarde dans la session
            $session->set("retour", $retour);
//
            suite:
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }

    /**
     * @Route("/edit/", name="edit")
     */
    public function edit(Request $request, SessionInterface $session)
    {

        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            $retour = $session->get("retour", []);
            if (!empty($retour[$id])) {//verification existance produit dans le panier

                $produit = $retour[$id]['produit'];
                $produit->setQuantite($quantite);
                $retour[$id]['produit'] = $produit;

                // On sauvegarde dans la session
                $session->set("retour", $retour);

                $res['id'] = 'ok';
                $res['panier'] = $quantite;

            } else {
                $res['id'] = 'no';
            }

            //$session->set("approv", $approv);
            $res['id'] = 'ok';
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }

    /**
     * @Route("/delete/", name="retour_delete")
     */
    public function delete(Request $request, ProduitRepository $repository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $retour = $session->get("retour", []);
        $id = $request->get('prod');
        foreach ($retour as $key => $item) {
            if ($item['id'] == $id) {
                unset($retour[$key]);
            }
        }
//        $id = $repository->find($request->get('prod'))->getId();
//        foreach ($approv as $key => $item) {
//            if ($item['produit']->getId() == $id) {
//                unset($approv[$id]);
//            }
//        }
        // On sauvegarde dans la session
        $session->set("retour", $retour);
        $res['id'] = 'ok';
        $res['nb'] = count($retour);
        $response = new Response();
        $response->headers->set('content-type', 'application/json');
        $re = json_encode($res);
        $response->setContent($re);
        return $response;
    }

    /**
     * @Route("/deleteAll/", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        $response = $this->redirectToRoute('commande_panier_panier', [], Response::HTTP_SEE_OTHER);
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
