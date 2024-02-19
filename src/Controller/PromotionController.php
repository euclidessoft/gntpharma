<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Entity\PromotionProduit;
use App\Form\PromotionType;
use App\Repository\ProduitRepository;
use App\Repository\PromotionProduitRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Commande_Promotion")
 */
class PromotionController extends AbstractController
{
    /**
     * @Route("/", name="promotion_index", methods={"GET"})
     */
    public function index(SessionInterface $session, PromotionRepository $promotionRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {


            $response = $this->render('promotion/admin/index.html.twig', [
                'promotions' => $promotionRepository->findAll(),
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
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach ($panier as $commande) {
                $dataPanier[] = [
                    'produit' => $commande['produit'],
                ];
            }
            $response = $this->render('promotion/index.html.twig', [
                'promotions' => $promotionRepository->findAll(),
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
     * @Route("/new", name="promotion_new", methods={"GET","POST"})
     */
    public function new(Request $request, ProduitRepository $produitRepository, SessionInterface $session): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $produits = $produitRepository->findAll();

            $promo = $session->get("promo", []);
            $dataPanier = [];

            foreach ($promo as $commande) {
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
            }
            $promotion = new Promotion();
            $form = $this->createForm(PromotionType::class, $promotion);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $promo = $session->get("promo", []);
                if (count($promo) >= 1) {
                    $promotion->setUser($this->getUser());
                    $em->persist($promotion);
                    foreach ($promo as $product) {
                        $produit = $produitRepository->find($product['produit']->getId());
                        $promotionproduit = new PromotionProduit($produit, $promotion);

                        $em->persist($promotionproduit);
                    }
                    $em->flush();
                    $session->remove("promo");
                    $this->addFlash('notice', 'Promotion crée');
                    $response = $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
                    $response->setSharedMaxAge(0);
                    $response->headers->addCacheControlDirective('no-cache', true);
                    $response->headers->addCacheControlDirective('no-store', true);
                    $response->headers->addCacheControlDirective('must-revalidate', true);
                    $response->setCache([
                        'max_age' => 0,
                        'private' => true,
                    ]);
                    return $response;
                }else{
                    $this->addFlash('danger', 'Veuillez ajouter des produits à la promotion');
                }
            }

            if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
                $id = $request->get('prod');// recuperation de id produit
                $quantite = $request->get('quantite');// recuperation de la quantite commamde
                if (empty($promo[$id])) {//verification existance produit dans le panier
                    $produit = $produitRepository->find($id); // recuperation de id produit dans la db

                    $produit->setQuantite($quantite);

                    $promo[$id] = [// placement produit et quantite dans le panier
                        "produit" => $produit,
                    ];

                    // On sauvegarde dans la session
                    $session->set("promo", $promo);

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

            $response = $this->render('promotion/admin/new.html.twig', [
                'promotion' => $promotion,
                'produits' => $produits,
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
     * @Route("/PromotionsCourantes", name="promotion_courante", methods={"GET","POST"})
     */
    public function courante(SessionInterface $session, PromotionRepository $promotionRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {


            $response = $this->render('promotion/admin/encours.html.twig', [
                'promotions' => $promotionRepository->Courante(),
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
        }  else if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);

            $response = $this->render('promotion/encours.html.twig', [
                'promotions' => $promotionRepository->CouranteClient(),
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
        }  else {
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
     * @Route("/add/", name="promotion_add")
     */
    public function add(Request $request, ProduitRepository $produitRepository, SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
        // On récupère le panier actuel
        $promo = $session->get("promo", []);
        if ($request->isXmlHttpRequest()) {// traitement de la requete ajax
            $id = $request->get('prod');// recuperation de id produit
            if (empty($promo[$id])) {//verification existance produit dans le panier
                $produit = $produitRepository->find($id); // recuperation de id produit dans la db

                $promo[$id] = [// placement produit et quantite dans le panier
                    "produit" => $produit,
                ];

                // On sauvegarde dans la session
                $session->set("promo", $promo);

                $res['id'] = 'ok';
                $res['ref'] = $produit->getReference();
                $res['designation'] = $produit->getDesigantion();
                $res['fabriquant'] = $produit->getFabriquant();

            } else {
                $res['id'] = 'no';
            }

            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }
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
     * @Route("/delete", name="promotion_delete")
     */
    public function promodelete(Request $request, ProduitRepository $repository, SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

        // On récupère le panier actuel
        $promo = $session->get("promo", []);
        $id = $repository->find($request->get('prod'))->getId();

        if (!empty($promo[$id])) {
            unset($promo[$id]);
        }

        // On sauvegarde dans la session
        $session->set("promo", $promo);
        $res['id'] = 'ok';
        $res['nb'] = count($promo);
        $response = new Response();
        $response->headers->set('content-type', 'application/json');
        $re = json_encode($res);
        $response->setContent($re);
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
     * @Route("/arreter", name="promotion_arreter")
     */
    public function arreter(Request $request, ProduitRepository $repository, SessionInterface $session)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

        // On récupère le panier actuel
        $promo = $session->get("promo", []);
        $id = $repository->find($request->get('prod'))->getId();

        if (!empty($promo[$id])) {
            unset($promo[$id]);
        }

        // On sauvegarde dans la session
        $session->set("promo", $promo);
        $res['id'] = 'ok';
        $res['nb'] = count($promo);
        $response = new Response();
        $response->headers->set('content-type', 'application/json');
        $re = json_encode($res);
        $response->setContent($re);
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
     * @Route("/{id}", name="promotion_show", methods={"GET"})
     */
    public function show(Promotion $promotion, PromotionProduitRepository $produitrepo, SessionInterface $session): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

        return $this->render('promotion/admin/show.html.twig', [
            'promotion' => $promotion,
            'produitspromotion' => $produitrepo->findBy(['promotion' => $promotion])
        ]);
        }
        elseif ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {
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

        return $this->render('promotion/show.html.twig', [
            'promotion' => $promotion,
            'produitspromotion' => $produitrepo->findBy(['promotion' => $promotion]),
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
     * @Route("/{id}/edit", name="promotion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Promotion $promotion): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion/admin/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cancel/{id}", name="promotion_cancel", methods={"POST"})
     */
    public function cancel(Request $request, Promotion $promotion): Response
    {
        if ($this->isCsrfTokenValid('delete' . $promotion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
           $promotionproduits = $entityManager->getRepository(PromotionProduit::class)->findBy(['promotion' => $promotion]);
           foreach ($promotionproduits as $promotionproduit){
               $entityManager->remove($promotionproduit);
            }
            $entityManager->remove($promotion);
            $entityManager->flush();
            $this->addFlash('notice', 'Promotion supprimée');
        }

        return $this->redirectToRoute('promotion_index', [], Response::HTTP_SEE_OTHER);
    }
}
