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
            'stock' => $repository->findAll(),
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
     * @Route("/{id}", name="produit_show", methods={"GET"})
     */
    public function produit(Produit $produit): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_STOCK')) {

            $response = $this->render('stock/produit_show.html.twig', [
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
// else if ($this->get('security.authorization_checker')->isGranted('ROLE_ROLE')) {
//
//            $response = $this->render('produit/show.html.twig', [
//                'produit' => $produit,
//            ]);
//            $response->setSharedMaxAge(0);
//            $response->headers->addCacheControlDirective('no-cache', true);
//            $response->headers->addCacheControlDirective('no-store', true);
//            $response->headers->addCacheControlDirective('must-revalidate', true);
//            $response->setCache([
//                'max_age' => 0,
//                'private' => true,
//            ]);
//            return $response;
  //    }
      else {
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
