<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Commande", name="commande_")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];
            $total = 0;

            foreach($panier as $commande){
//                $product = $produitRepository->find($id);
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
//                $total += $product->getPrix() * $quantite;
            }

            $response = $this->render('produit/index.html.twig', [
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
     * @Route("/add/", name="add")
     */
    public function add(Request $request, ProduitRepository $produitRepository, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        if( $request->isXmlHttpRequest() )
        {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            if(empty($panier[$id])){//verification existance produit dans le panier
                $produit = $produitRepository->find($id); // recuperation de id produit dans la db
                if($produit->getMincommande() <= $quantite) {// verification quantite minimum
                    $produit->setQuantite($quantite);

                    $panier[$id] = [// placement produit et quantite dans le panier
                        "produit" => $produit,
                    ];

                    // On sauvegarde dans la session
                    $session->set("panier", $panier);

                    $res['id'] = 'ok';
                    $res['panier'] = count($panier);

                    $res['id'] = 'min';
                }
            }else{
                $res['id'] = 'no';
            }

            $response = new Response();
            $response->headers->set('content-type','application/json');
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

        if( $request->isXmlHttpRequest() )
        {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            $quantite = $request->get('quantite');// recuperation de la quantite commamde
            if(empty($panier[$id])){//verification existance produit dans le panier
                $panier = $session->get("panier", []);
                $produit = $panier[$id]['produit'];
                $produit->setQuantite($quantite);
                $panier[$id]['produit'] = $produit;

                    // On sauvegarde dans la session
                    $session->set("panier", $panier);

                    $res['id'] = 'ok';
                    $res['panier'] = $quantite;

            }else{
                $res['id'] = 'no';
            }

            $response = new Response();
            $response->headers->set('content-type','application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Produit $Produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $Produit->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("commande_panier_panier");
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

}
