<?php

namespace App\Controller;

use App\Entity\Approvisionnement;
use App\Entity\Approvisionner;
use App\Entity\Produit;
use App\Repository\ApprovisionnementRepository;
use App\Repository\ApprovisionnerRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Stock" , name="stock_")
 */
class ApprovisionnementController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function stock(ProduitRepository $produitRepository): Response
    {
        return $this->render('approvisionnement/stock.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Approvisionnement", name="appro_index", methods={"GET"})
     */
    public function index(SessionInterface $session, ApprovisionnementRepository $approvisionnementRepository, ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        $approv = $session->get("approv", []);
        $dataPanier = [];
        $total = 0;

        foreach ($approv as $commande) {
            $dataPanier[] = [
                "produit" => $commande['produit'],
            ];
        }


        return $this->render('approvisionnement/index.html.twig', [
            'approvisionnements' => $approvisionnementRepository->findAll(),
            'produits' => $produits,
            'panier' => $dataPanier,
        ]);
    }

    /**
     * @Route("/Historique", name="historique", methods={"GET"})
     */
    public function historique(ApprovisionnerRepository $repository): Response
    {
        return $this->render('approvisionnement/historique.html.twig', [
            'approvisionnements' => $repository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="produit_show", methods={"GET"})
     */
    public function produit(Produit $produit): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $response = $this->render('approvisionnement/produit_show.html.twig', [
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
        } else if ($this->get('security.authorization_checker')->isGranted('ROLE_ROLE')) {

            $response = $this->render('produit/show.html.twig', [
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
     * @Route("/add/", name="appro_add")
     */
    public function add(Request $request, ProduitRepository $produitRepository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $approv = $session->get("approv", []);
        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            if (empty($approv[$id])) {//verification existance produit dans le panier
                $produit = $produitRepository->find($id); // recuperation de id produit dans la db

                $produit->setQuantite($quantite);

                $approv[$id] = [// placement produit et quantite dans le panier
                    "produit" => $produit,
                ];

                // On sauvegarde dans la session
                $session->set("approv", $approv);

                $res['id'] = 'ok';
                $res['ref'] = $produit->getReference();
                $res['designation'] = $produit->getDesigantion();
                $res['fabriquant'] = $produit->getFabriquant();
                $res['quantite'] = $produit->getQuantite();

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
     * @Route("/edit", name="edit")
     */
    public function edit(Request $request, SessionInterface $session)
    {

        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            $approv = $session->get("approv", []);
            if (!empty($approv[$id])) {//verification existance produit dans le panier

                $produit = $approv[$id]['produit'];
                $produit->setQuantite($quantite);
                $approv[$id]['produit'] = $produit;

                // On sauvegarde dans la session
                $session->set("approv", $approv);

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
     * @Route("/delete", name="delete")
     */
    public function delete(Request $request, ProduitRepository $repository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $approv = $session->get("approv", []);
        $id = $repository->find($request->get('prod'))->getId();

        if (!empty($approv[$id])) {
            unset($approv[$id]);
        }

        // On sauvegarde dans la session
        $session->set("approv", $approv);
        $res['id'] = 'ok';
        $res['nb'] = count($approv);
        $response = new Response();
        $response->headers->set('content-type', 'application/json');
        $re = json_encode($res);
        $response->setContent($re);
        return $response;
    }

    /**
     * @Route("/delete", name="delete_all")
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

   /**
 * @Route("/valider", name="valider")
 */
    public function valider(SessionInterface $session, ProduitRepository $produitRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $produits = $produitRepository->findAll();
            $approv = $session->get("approv", []);


            if (count($approv) >= 1) {
                $em = $this->getDoctrine()->getManager();
                $approvisionner = new  Approvisionner();
                $approvisionner->setUser($this->getUser());
                $em->persist($approvisionner);
                foreach ($approv as $product) {
                    $produit = $produitRepository->find($product['produit']->getId());
                    $approvisionnenment = new Approvisionnement($produit,$approvisionner,$product['produit']->getQuantite());
                    $produit->approvisionner($product['produit']->getQuantite());
                    $em->persist($produit);
                    $em->persist($approvisionnenment);
                }
                $em->flush();
                $session->remove("approv");
            }
            $this->addFlash('notice', 'Approvisionnement réussie');
            $response = $this->redirectToRoute('stock_historique');
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
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Approvisionner $approvisionner, ApprovisionnementRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $response = $this->render('approvisionnement/show.html.twig', [
                'approvisionner' => $approvisionner,
                'approvisionnements' => $repository->findBy(['approvisionner' => $approvisionner]),
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

}
