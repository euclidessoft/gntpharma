<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\ProduitType;
use App\Repository\ApprovisionnementRepository;
use App\Repository\ApprovisionnerRepository;
use App\Repository\CommandeProduitRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach($panier as $commande){
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
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
        }
        else if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $response = $this->render('produit/admin/index.html.twig', [
                'produits' => $produitRepository->findAll(),
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
        } else  {
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
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $produit = new Produit();
            $form = $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if($produit->getPrix() < $produit->getPrixpublic()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($produit);
                    $entityManager->flush();
                    $this->addFlash('notice', 'Nouveau Produit Ajouté');

                    $response = $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
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
                else{
                    $this->addFlash('notice', 'Prix publique doit etre strictement superieur au prix de session');
                }
            }
            $response = $this->render('produit/admin/new.html.twig', [
                'produit' => $produit,
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
     * @Route("/Nouveaute", name="produit_nouveaute", methods={"GET"})
     */
    public function nouveaute(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach($panier as $commande){
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
            }

            $response = $this->render('produit/nouveaute.html.twig', [
                'produits' => $produitRepository->nouveaute(),
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
        }else  {
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
     * @Route("/Arrivage", name="produit_arrivage", methods={"GET"})
     */
    public function arrivage(SessionInterface $session, ApprovisionnerRepository $approvisionnerRepository, ApprovisionnementRepository $approvisionnementRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach($panier as $commande){
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
            }
            $approvisionner = $approvisionnerRepository->arrivage();//recuperation des approvisioonement de moins de 7 jours
            if(count($approvisionner) > 1){ // si plus d' un appron
                $appro = [];
                foreach ($approvisionner as $item){// mettre les id approvisionner dans un tableau
                    $appro[] = $item->getId();
                }
                $approvisionnements = $approvisionnementRepository->arrivage($appro);// recuperation des approvisionnement des id dans le tableau
            }else{
                $approvisionnements = $approvisionnementRepository->findBy(['approvisionner' => $approvisionner]);
            }

            $response = $this->render('produit/arrivage.html.twig', [
                'approvisionnements' => $approvisionnements,
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
        }else  {
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
     * @Route("/Vente", name="produit_vente", methods={"GET"})
     */
    public function vente(SessionInterface $session, CommandeProduitRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach($panier as $commande){
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
            }

            $ventemensuel = $repository->ventemensuel();
            $venteannuel = $repository->venteannuel();//


            $response = $this->render('produit/vente.html.twig', [
                'ventemensuel' => $ventemensuel,
                'venteannuel' => $venteannuel,
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
        }else  {
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
     * @Route("/MonTop", name="produit_top", methods={"GET"})
     */
    public function top(SessionInterface $session, CommandeProduitRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            $panier = $session->get("panier", []);
            $dataPanier = [];

            foreach($panier as $commande){
                $dataPanier[] = [
                    "produit" => $commande['produit'],
                ];
            }

            $topmensuel = $repository->topmensuel($this->getUser());
//            $venteannuel = $repository->topannuel($this->getUser());//


            $response = $this->render('produit/top.html.twig', [
                'ventemensuel' => $topmensuel,
//                'venteannuel' => $venteannuel,
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
        }else  {
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
     * @Route("/{id}", name="produit_show", methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $response = $this->render('produit/admin/show.html.twig', [
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
     * @Route("/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if($produit->getPrix() < $produit->getPrixpublic()){
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('notice', 'Produit modifie avec succes');

                    $response = $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
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
                else{
                    $this->addFlash('notice', 'Prix publique doit etre strictement superieur au prix de session');
                }


            }

            $response = $this->render('produit/admin/edit.html.twig', [
                'produit' => $produit,
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
     * @Route("/{id}", name="produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
    }


}
