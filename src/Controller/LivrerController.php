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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            //$panier = $session->get("panier", []);

            $response = $this->render('livrer/history.html.twig', [
                'livrers' => $repository->findAll(),
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
        if($commande->getLivraison()){
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
            $commandeproduit->setStock($stock);
            $listcommande[] = $commandeproduit;
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
    public function reste(Commande $commande,LivrerResteRepository $livrerResteRepository, CommandeProduitRepository $comprodrepository, ProduitRepository $repository, SessionInterface $session): Response
    {// traitement livraison

        $session->remove("livraison", []);
        $commandeproduits = $livrerResteRepository->findBy(['commande' => $commande]);
        $listcommande = [];
        foreach ($commandeproduits as $commandeproduit) {
            $stock = $repository->find($commandeproduit->getProduit()->getId())->getStock();
            $commandeproduit->setStock($stock);
            $listcommande[] = $commandeproduit;
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
     * @Route("/Historique/{id}", name="historique_show", methods={"GET"})
     */
    public function history_show(Livrer $livrer, LivrerProduitRepository $livrerProduitRepository, ProduitRepository $repository, SessionInterface $session): Response
    {// traitement livraison
        if($livrer->getCommande()->getLivraison()){
            $commandeproduits = $livrerProduitRepository->findBy(['livrer' => $livrer]);


            $response = $this->render('livrer/history_show.html.twig', [
                'commandes' => $commandeproduits,
                'commandereference' => $livrer->getCommande(),
                'livrer' => $livrer,
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
     * @Route("/valider/{id}", name="valider")
     */
    public function valider(Commande $commande, CommandeProduitRepository $comprodrepository, ProduitRepository $repository, SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $panier = $session->get("livraison", []);
            $em = $this->getDoctrine()->getManager();
            $commandeproduits = $comprodrepository->findBy(['commande' => $commande]);

            $livrer = new Livrer($commande, $this->getUser());
            $commande->setLivraison(true);
            $reussi = false;
            foreach ($commandeproduits as $commandeproduit) {
                $produit = $repository->find($commandeproduit->getProduit()->getId());

                if (empty($panier[$produit->getId()])) {// livraison totale

                    if($produit->livraison($commandeproduit->getQuantite())){
                        $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $commandeproduit->getQuantite(), $produit->getStock());
                        $em->persist($livrerProduit);
                        $reussi = true;
                    }

                } elseif ($commandeproduit->getQuantite() > $produit->getStock()) {// livraison totale

                    if($produit->livraison($produit->getStock())){
                        $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $produit->getStock(), $produit->getStock());
                        $reste = new LivrerReste($livrer, $commande, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()]);
                        $em->persist($reste);
                        $em->persist($livrerProduit);
                        $livrer->setReste(true);
                        $reussi = true;
                    }

                } else {// livraison partielle
                    if($produit->livraison($panier[$produit->getId()])) {
                        $livrerProduit = new LivrerProduit($livrer, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()], $produit->getStock());
                        $reste = new LivrerReste($livrer, $commande, $produit, $commandeproduit->getQuantite(), $panier[$produit->getId()]);
                        $em->persist($reste);
                        $em->persist($livrerProduit);
                        $livrer->setReste(true);
                        $reussi = true;
                    }
                }
                $em->persist($produit);
            }
            if($reussi) {
                $em->persist($livrer);
                $em->persist($commande);
                $em->flush();
                $this->addFlash('notice', 'Livraison enregistrée avec succés');
                $response = $this->redirectToRoute('livraison_index');
            }else{
                $this->addFlash('echec', 'verifier les quantités avant validation');
                $response = $this->redirectToRoute('livraison_show',['id'=>$commande->getId()]);
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
