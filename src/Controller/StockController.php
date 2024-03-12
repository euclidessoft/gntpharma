<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Candidature;
use App\Entity\Produit;
use App\Form\CandidatureType;
use App\Repository\ImageRepository;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
       }
      else {
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
       }
      else {
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

}
