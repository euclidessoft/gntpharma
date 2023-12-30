<?php

namespace App\Controller;

use App\Entity\Approvisionnement;
use App\Entity\Produit;
use App\Form\ApprovisionnementType;
use App\Repository\ApprovisionnementRepository;
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
     * @Route("/", name="appro_index", methods={"GET"})
     */
    public function index(SessionInterface $session, ApprovisionnementRepository $approvisionnementRepository, ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        $approv = $session->get("approv", []);
        $dataPanier = [];
        $total = 0;

        foreach ($approv as $commande) {
//                $product = $produitRepository->find($id);
            $dataPanier[] = [
                "produit" => $commande['produit'],
            ];
//                $total += $product->getPrix() * $quantite;
        }

        return $this->render('approvisionnement/index.html.twig', [
            'approvisionnements' => $approvisionnementRepository->findAll(),
            'produits' => $produits,
            'panier' => $dataPanier,
        ]);
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

//    /**
//     * @Route("/{id}", name="approvisionnement_delete", methods={"POST"})
//     */
//    public function delete(Request $request, Approvisionnement $approvisionnement): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$approvisionnement->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($approvisionnement);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('approvisionnement_index', [], Response::HTTP_SEE_OTHER);
//    }
}
