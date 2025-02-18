<?php

namespace App\Controller;

use App\Entity\Paie;
use App\Form\PaieType;
use App\Repository\HeureSuplementaireRepository;
use App\Repository\PaieRepository;
use App\Repository\PrimeRepository;
use App\Repository\RetenueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Paie")
 */
class PaieController extends AbstractController
{
    /**
     * @Route("/", name="paie_index")
     */
    public function index(PaieRepository $paieRepository): Response
    {
        $paie = $paieRepository->findAll();
        return $this->render('paie/index.html.twig',[
            'paie' => $paie,
        ]);
    }

    /**
     * @Route("/new", name="paie_new", methods={"POST","GET"})
     */
    public function new(Request $request,PrimeRepository $primeRepository, HeureSuplementaireRepository $heureSuplementaireRepository,RetenueRepository $retenueRepository): Response
    {
        $paie = new Paie();
        $form = $this->createForm(PaieType::class, $paie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // Récupération du salaire de base depuis le poste
            $employe = $paie->getEmploye();
            $poste = $employe->getPoste();
            $salaireDeBase = $poste->getSalaire();
            $paie->setSalaireBase($salaireDeBase);
            // Récupérer les éléments du salaire
            $totalPrimes = $primeRepository->getTotalPrimesByEmploye($employe) ?? 0;
            $totalHeureSup = $heureSuplementaireRepository->getTotalHeuresByEmploye($employe) ?? 0;
            $totalRetenue = $retenueRepository->getTotalRetenuesByEmploye($employe) ?? 0;
            // Calcul du salaire net
            $salaireNet = $salaireDeBase + $totalPrimes + $totalHeureSup - $totalRetenue;
            $paie->setTotalPrime($totalPrimes);
            $paie->setTotalheureSup($totalHeureSup);
            $paie->setTotalRetenue($totalRetenue);
            $paie->setSalaireNet($salaireNet);
            $entityManager->persist($paie);
            $entityManager->flush();
            return $this->redirectToRoute('paie_index');
        }
        return $this->render('paie/new.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
