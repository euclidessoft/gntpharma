<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Entity\User;
use App\Form\ReponseType;
use App\Repository\CommandeProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Account")
 */
class DetteController extends AbstractController
{
    /**
     * @Route("/Dette/{user}", name="finance_dette", methods={"GET","POST"})
     */
    public function index(User $user, Request $request,SessionInterface $session, CommandeRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {



            return $this->render('dette/index.html.twig', [
                'commandes' => $repository->findBy(['payer' => false, 'user' => $user]),
                'panier' => $session->get('panier', []),
            ]);

        }else{
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
     * @Route("/Dette_show/{commande}", name="finance_dette_show", methods={"GET","POST"})
     */
    public function show(Commande $commande, Request $request,SessionInterface $session, CommandeProduitRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')) {

            if($commande->getUser() == $this->getUser()) {

                return $this->render('dette/show.html.twig', [
                    'commande' => $commande,
                    'commandeproduits' => $repository->findBy(['commande' => $commande]),
                    'panier' => $session->get('panier', []),
                ]);
            }
            else{
                $response = $this->redirectToRoute('finance_dette', ['user' => $this->getUser()->getId()]);
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

        }else{
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
