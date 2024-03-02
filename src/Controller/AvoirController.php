<?php

namespace App\Controller;

use App\Entity\Avoir;
use App\Entity\Commande;
use App\Entity\LivrerReste;
use App\Form\AvoirType;
use App\Repository\AvoirRepository;
use App\Repository\CommandeProduitRepository;
use App\Repository\LivrerRepository;
use App\Repository\LivrerResteRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Avoir")
 */
class AvoirController extends AbstractController
{
    /**
     * @Route("/", name="avoir_index", methods={"GET"})
     */
    public function index(AvoirRepository $avoirRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/index.html.twig', [
                'avoirs' => $avoirRepository->findAll(),
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
     * @Route("/Choix_Avoir", name="avoir_choix", methods={"GET"})
     */
    public function choix(AvoirRepository $avoirRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/choix.html.twig');
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
     * @Route("/Reste", name="avoir_reste", methods={"GET"})
     */
    public function reste(LivrerRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('avoir/reste.html.twig', [
                'livrers' => $repository->findBy(['reste' => true]),
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
     * @Route("/new/reste/{id}", name="avoir_new_reste", methods={"GET","POST"})
     */
    public function new(Commande $commande, LivrerResteRepository $livrerResteRepository, CommandeProduitRepository $comprodrepository, ProduitRepository $repository, SessionInterface $session): Response
    {// traitement livraison

        $session->remove("livraison");
        $commandeproduits = $livrerResteRepository->findBy(['commande' => $commande]);
        $listcommande = [];
        foreach ($commandeproduits as $commandeproduit) {
            $stock = $repository->find($commandeproduit->getProduit()->getId())->getStock();
            $commandeproduit->setStock($stock);
            $listcommande[] = $commandeproduit;
        }
        $session->set("traitement", []);
        $response = $this->render('livrer/reste_show.html.twig', [
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

//    public function new(Request $request, LivrerReste $reste): Response
//    {
//        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
//            $avoir = new Avoir();
//            $form = $this->createForm(AvoirType::class, $avoir);
//            $form->handleRequest($request);
//
//            if ($form->isSubmitted() && $form->isValid()) {
//                $entityManager = $this->getDoctrine()->getManager();
//                $avoir->setAdmin($this->getUser());
//                $avoir->setClient($this->getUser());
//                $entityManager->persist($avoir);
//                $entityManager->flush();
//
//                return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
//            }
//
//            return $this->render('avoir/new.html.twig', [
//                'avoir' => $avoir,
//                'form' => $form->createView(),
//            ]);
//        } else {
//            $response = $this->redirectToRoute('security_logout');
//            $response->setSharedMaxAge(0);
//            $response->headers->addCacheControlDirective('no-cache', true);
//            $response->headers->addCacheControlDirective('no-store', true);
//            $response->headers->addCacheControlDirective('must-revalidate', true);
//            $response->setCache([
//                'max_age' => 0,
//                'private' => true,
//            ]);
//            return $response;
//        }
//    }

    /**
     * @Route("/{id}", name="avoir_show", methods={"GET"})
     */
    public function show(Avoir $avoir): Response
    {
        return $this->render('avoir/show.html.twig', [
            'avoir' => $avoir,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avoir_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Avoir $avoir): Response
    {
        $form = $this->createForm(AvoirType::class, $avoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avoir/edit.html.twig', [
            'avoir' => $avoir,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avoir_delete", methods={"POST"})
     */
    public function delete(Request $request, Avoir $avoir): Response
    {
        if ($this->isCsrfTokenValid('delete' . $avoir->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($avoir);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avoir_index', [], Response::HTTP_SEE_OTHER);
    }
}
