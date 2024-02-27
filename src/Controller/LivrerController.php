<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livrer;
use App\Entity\LivrerProduit;
use App\Entity\LivrerReste;
use App\Form\LivrerType;
use App\Repository\CommandeProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\LivrerProduitRepository;
use App\Repository\LivrerRepository;
use App\Repository\LivrerResteRepository;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/livraison", name="livraison_")
 */
class LivrerController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(LivrerRepository $livrerRepository, CommandeRepository $repository): Response
    {

        return $this->render('livrer/index.html.twig', [
            'livrers' => $livrerRepository->findBy(['reste' => true]),
            'commandes' => $repository->findBy(['suivi' => true, 'livraison' => false]),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $livrer = new Livrer();
        $form = $this->createForm(LivrerType::class, $livrer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livrer);
            $entityManager->flush();

            return $this->redirectToRoute('livrer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livrer/new.html.twig', [
            'livrer' => $livrer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Historique/", name="historique")
     */
    public function histo_admin(LivrerRepository $repository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {
            //$panier = $session->get("panier", []);

            $response = $this->render('livrer/history.html.twig', [
                'livrers' => $repository->historique(),
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
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Commande $commande, CommandeProduitRepository $comprodrepository, ProduitRepository $repository, SessionInterface $session): Response
    {// traitement livraison
        if ($commande->getLivraison()) {
            $this->addFlash('notice', 'Livraison deja traite');
            $response = $this->redirectToRoute('livraison_index');
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
        $session->remove("livraison", []);
        $commandeproduits = $comprodrepository->findBy(['commande' => $commande]);
        $listcommande = [];
        foreach ($commandeproduits as $commandeproduit) {
            $stock = $repository->find($commandeproduit->getProduit()->getId())->getStock();
            $commandeproduit->setStock($stock);// affectation produit pour verification
            $listcommande[] = $commandeproduit;

            $session->set($commandeproduit->getProduit()->getId(), null);
        }

        $response = $this->render('livrer/show.html.twig', [
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
    }

    /**
     * @Route("/Reste/{id}", name="reste_show", methods={"GET"})
     */
    public function reste(Commande $commande, LivrerResteRepository $livrerResteRepository, CommandeProduitRepository $comprodrepository, ProduitRepository $repository, SessionInterface $session): Response
    {// traitement livraison

        $session->remove("livraison", []);
        $commandeproduits = $livrerResteRepository->findBy(['commande' => $commande]);
        $listcommande = [];
        foreach ($commandeproduits as $commandeproduit) {
            $stock = $repository->find($commandeproduit->getProduit()->getId())->getStock();
            $commandeproduit->setStock($stock);
            $listcommande[] = $commandeproduit;
        }

        $response = $this->render('livrer/reste_show.html.twig', [
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
    }

    /**
     * @Route("/Historique/{id}", name="historique_show", methods={"GET"})
     */
    public function history_show(Commande $commande, LivrerRepository $livrerRepository, LivrerProduitRepository $livrerProduitRepository, ProduitRepository $repository, SessionInterface $session): Response
    {// traitement livraison
        if ($commande->getLivraison()) {
            $livrer = $livrerRepository->findBy(['commande' => $commande]);
            $histo = [];
            foreach ($livrer as $item) {
                $histo[] = $item->getId();
            }

            $commandeproduits = $livrerProduitRepository->historique($histo);


            $response = $this->render('livrer/history_show.html.twig', [
                'commandes' => $commandeproduits,
                'commandereference' => $commande,
//                'livrer' => $livrer,
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

    }

    /**
     * @Route("/add/", name="modif")
     */
    public function modif(Request $request, ProduitRepository $produitRepository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $livraison = $session->get("livraison", []);
        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            if (empty($livraison[$id])) {//verification existance produit dans le panier
//                $produit = $produitRepository->find($id); // recuperation de id produit dans la db
//                if($produit->getMincommande() <= $quantite) {// verification quantite minimum
//                    $produit->setQuantite($quantite);

                $livraison[$id] = $quantite;

                // On sauvegarde dans la session
                $session->set("livraison", $livraison);

                $res['id'] = 'ok';
//                    $res['panier'] = count($panier);
//                }
            } else {
                $res['id'] = 'no';
            }

            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }

    /**
     * @Route("/details/", name="details")
     */
    public function details(Request $request, StockRepository $repository, SessionInterface $session)
    {
        // On récupère le panier actuel
        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
                $stock = $repository->findBy(['produit'=> $id]); // recuperation de id produit dans la db
                foreach ($stock as $stockproduit){
                    $res[]= [
                        'lot' => $stockproduit->getLot(),
                        'peremption' => $stockproduit->getPeremption(),
                        'quantite' => $stockproduit->getQuantite(),
                    ];
                }



//                $res['id'] = 'ok';

            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }
    /**
     * @Route("/valider_produit/", name="valider_produit")
     */
    public function validerproduit(Request $request, StockRepository $repository, SessionInterface $session)
    {
        // On récupère le panier actuel
        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax


            $id = $request->get('prod');// recuperation de id produit
            $lot = $request->get('lot');// recuperation de id produit
//            $peremption = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            $livraison = $session->get($id, []);
            if($livraison >= 1) {
                foreach ($livraison as $key => $item) {
                    if ($item['id'] == $id && $item['lot'] == $lot) {
                        $livraison[$key] = [// remplacement produit et quantite dans le panier
                            "id" => $id,
                            "lot" => $lot,
                            'quantite' => $quantite,
                        ];
                    } else {
                        $livraison[] = [// placement produit et quantite dans le panier
                            "id" => $id,
                            "lot" => $lot,
                            'quantite' => $quantite,
                        ];

                    }
                }
                $session->set($id, $livraison);
            }



               $res['id'] = 'ok';

            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }

    /**
     * @Route("/valider/{id}", name="valider")
     */
    public function valider(Commande $commande, CommandeProduitRepository $comprodrepository, ProduitRepository $repository, SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {

            $panier = $session->get("livraison", []);
            $em = $this->getDoctrine()->getManager();
            $commandeproduits = $comprodrepository->findBy(['commande' => $commande]);

            $livrer = new Livrer($commande, $this->getUser());
            $commande->setLivraison(true);
            $commande->setDatelivrer(new \DateTime());

            foreach ($commandeproduits as $commandeproduit) {
                $produit = $repository->find($commandeproduit->getProduit()->getId());
                $ug = 0;
                // traitement promotion floor()
                if (!empty($commandeproduit->getPromotion())) {
                    if (!empty($commandeproduit->getPromotion()->getPremier())) {
                        if ($commandeproduit->getQuantite() / $commandeproduit->getPromotion()->getTroisieme() >= 1) {

                            $unite = floor($commandeproduit->getQuantite() / $commandeproduit->getPromotion()->getTroisieme());
                            $ug = $ug + $unite * $commandeproduit->getPromotion()->getUgtroisieme();
                            $suite = $commandeproduit->getQuantite() - $unite * $commandeproduit->getPromotion()->getTroisieme();

                            if ($suite / $commandeproduit->getPromotion()->getDeuxieme() >= 1) {

                                $unite = floor($suite / $commandeproduit->getPromotion()->getDeuxieme());//round
                                $ug = $ug + $unite * $commandeproduit->getPromotion()->getUgdeuxieme();
                                $suite = $suite - $unite * $commandeproduit->getPromotion()->getDeuxieme();

                                if ($suite / $commandeproduit->getPromotion()->getPremier() >= 1) {
                                    $unite = floor($suite / $commandeproduit->getPromotion()->getPremier());//round
                                    $ug = $ug + $unite * $commandeproduit->getPromotion()->getUgpremier();
                                }

                            } elseif ($suite / $commandeproduit->getPromotion()->getPremier() >= 1) {
                                $unite = floor($suite / $commandeproduit->getPromotion()->getPremier());//round
                                $ug = $ug + $unite * $commandeproduit->getPromotion()->getUgpremier();
                            }

                        }
                        elseif ($commandeproduit->getQuantite() / $commandeproduit->getPromotion()->getDeuxieme() >= 1) {


                            $unite = floor($commandeproduit->getQuantite() / $commandeproduit->getPromotion()->getDeuxieme());//round
                            $ug = $ug + $unite * $commandeproduit->getPromotion()->getUgdeuxieme();
                            $suite = $commandeproduit->getQuantite() - $unite * $commandeproduit->getPromotion()->getDeuxieme();

                            if ($suite / $commandeproduit->getPromotion()->getPremier() >= 1) {
                                $unite = floor($suite / $commandeproduit->getPromotion()->getPremier());//round
                                $ug = $ug + $unite * $commandeproduit->getPromotion()->getUgpremier();
                            }
                        }
                        elseif ($commandeproduit->getQuantite() / $commandeproduit->getPromotion()->getPremier() >= 1) {
                            $unite = floor($commandeproduit->getQuantite() / $commandeproduit->getPromotion()->getPremier());//round
                            $ug = $ug + $unite * $commandeproduit->getPromotion()->getUgpremier();

                        }
                    }
                }
                // fin traitement promotion

                if (empty($panier[$produit->getId()])) {// livraison sans modification du gestionnaire
                    if ($commandeproduit->getQuantite() > $produit->getStock() && $produit->getStock() > 0) {// livraison quantite superieur au stock
                        $quantite = $produit->getStock();
                        if ($produit->livraison($produit->getStock())) {
                            $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $quantite, $produit->getStock(), $commande);
                            $reste = new LivrerReste($livrer, $commande, $produit, $commandeproduit->getQuantite(), $quantite);
                            $livrerProduit->setReste(true);
                            $em->persist($reste);
                            $em->persist($livrerProduit);
                            $livrer->setReste(true);
                        } else {
                            goto terminer;
                        }

                    } else {// livraison normale

                        if (($produit->getStock() - ($commandeproduit->getQuantite() + $ug)) > 0) {
                            $produit->livraison($commandeproduit->getQuantite()+$ug);
                            $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $commandeproduit->getQuantite()+ $ug, $produit->getStock(), $commande);
                            if($ug != 0) $livrerProduit->setPromotion($commandeproduit->getPromotion());
                            $em->persist($livrerProduit);
                        }
                        elseif (($produit->getStock() - $commandeproduit->getQuantite()) == 0) {
                            $produit->livraison($commandeproduit->getQuantite()+$ug);
                            $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $commandeproduit->getQuantite()+ $ug, $produit->getStock(), $commande);
                            if($ug != 0) $livrerProduit->setPromotion($commandeproduit->getPromotion());
                            $em->persist($livrerProduit);
                        } else {
                            goto terminer;
                        }
                    }

                } else {// livraison avce modification du gestionnaire
                    if (($produit->getStock() - $panier[$produit->getId()]) > 0) {
                        $produit->livraison($panier[$produit->getId()]);
                        $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()], $produit->getStock(), $commande);
                        $reste = new LivrerReste($livrer, $commande, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()]);
                        $livrerProduit->setReste(true);
                        $em->persist($reste);
                        $em->persist($livrerProduit);
                        $livrer->setReste(true);
                    } else {
                        goto terminer;
                    }
                }
                $em->persist($produit);
            }

            $em->persist($livrer);
            $em->persist($commande);
            $em->flush();
            $this->addFlash('notice', 'Livraison enregistrée avec succés');
            $response = $this->redirectToRoute('livraison_index');

            $session->remove('livraison');

            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;

            terminer:
            $this->addFlash('echec', 'verifier les quantités avant validation');
            $response = $this->redirectToRoute('livraison_show', ['id' => $commande->getId()]);

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
     * @Route("/restevalider/{id}", name="reste_valider")
     */
    public function reste_valider(Commande $commande, LivrerRepository $livrerrepository, LivrerResteRepository $livrerResteRepository, ProduitRepository $repository, SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {

            $panier = $session->get("livraison", []);
            $em = $this->getDoctrine()->getManager();
            $commandeproduits = $livrerResteRepository->findBy(['commande' => $commande]);
            $oldlivrer = $livrerrepository->findOneBy(['commande' => $commande]);
            $oldlivrer->setReste(false);
            $em->persist($oldlivrer);

            $livrer = new Livrer($commande, $this->getUser());
            $commande->setLivraison(true);
            $reussi = false;
            foreach ($commandeproduits as $commandeproduit) {
                $produit = $repository->find($commandeproduit->getProduit()->getId());

                if (empty($panier[$produit->getId()])) {// livraison totale

                    if ($produit->livraison($commandeproduit->reste())) {
                        $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $commandeproduit->reste(), $produit->getStock(), $commande);
                        $livrerProduit->setRestealivrer(0);
                        $em->persist($livrerProduit);
                        $reussi = true;
                    }

                }
//                elseif ($commandeproduit->getQuantite() > $produit->getStock()) {// livraison totale
//
//                    if($produit->livraison($produit->getStock())){
//                        $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $produit->getStock(), $produit->getStock());
//                        $reste = new LivrerReste($livrer, $commande, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()]);
//                        $em->persist($reste);
//                        $em->persist($livrerProduit);
//                        $livrer->setReste(true);
//                        $reussi = true;
//                    }
//
//                }
//                else {// livraison partielle
//                    if($produit->livraison($panier[$produit->getId()])) {
//                        $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()], $produit->getStock());
//                        $reste = new LivrerReste($livrer, $commande, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()]);
//                        $em->persist($reste);
//                        $em->persist($livrerProduit);
//                        $livrer->setReste(true);
//                        $reussi = true;
//                    }
//                }
                $em->persist($produit);
            }
            if ($reussi) {
                $em->persist($livrer);
                $em->persist($commande);
                $em->flush();
                $this->addFlash('notice', 'Livraison enregistrée avec succés');
                $response = $this->redirectToRoute('livraison_index');
            } else {
                $this->addFlash('echec', 'verifier les quantités avant validation');
                $response = $this->redirectToRoute('livraison_reste_show', ['id' => $commande->getId()]);
            }

            $session->remove('livraison');

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


    /**`
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Livrer $livrer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $livrer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livrer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livrer_index', [], Response::HTTP_SEE_OTHER);
    }
}
