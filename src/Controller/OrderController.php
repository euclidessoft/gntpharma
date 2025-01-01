<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/order", name="order_")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $panier = 1;
        return $this->render('commande/allcommande.html.twig', [
            'panier' => $panier,
        ]);
    }
    
}
