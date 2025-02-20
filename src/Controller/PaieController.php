<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\HeureSuplementaire;
use App\Entity\Paie;
use App\Entity\Prime;
use App\Entity\Sanction;
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
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $employes = $entityManager->getRepository(Employe::class)->findAll();
        $paies = [];
        $currentMonth = (new \DateTime());

        foreach ($employes as $employe) {
            //Recuperations des Primes et Heure Supplementaire
            $primes = $entityManager->getRepository(Prime::class)->findBy([
                'employe' => $employe,
                'createdAt' => $currentMonth,
            ]);
            $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findBy([
                'employe' => $employe,
                'createdAt' => $currentMonth,
            ]);

            $sanctions = $entityManager->getRepository(Sanction::class)->findBy([
                'employe' => $employe,
                'createdAt' => $currentMonth,
            ]);
            $salaireDeBase = $employe->getPoste()->getSalaire();

            $paies[] = [
                'employe' => $employe,
                'salaireBase' => $salaireDeBase,
                'prime' => $primes,
                'heureSup' => $heureSup,
            ];
        }
        return $this->render('paie/index.html.twig', [
            'paie' => $paies,
        ]);
    }

    /**
     * @Route("/Bulletin", name="paie_bulletin", methods={"GET"})
     */
    public function bulletin(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $employes = $entityManager->getRepository(Employe::class)->findAll();
        $bulletins = [];

        foreach ($employes as $employe) {
            $salaireDeBase = $employe->getPoste()->getSalaire();
            $salaireJournaliere = $salaireDeBase / 30;

            //Recuperations des Primes et Heure Supplementaire
            $primes = $entityManager->getRepository(Prime::class)->findBy(['employe' => $employe]);
            $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findBy(['employe' => $employe]);
            $sanctions = $entityManager->getRepository(Sanction::class)->findBy(['employe' => $employe]);

            $retenues = [];
            foreach ($sanctions as $sanction) {
                if ($sanction->getTypeSanction()->getNom() === 'ponction salarial') {
                    $nombreJours = $sanction->getNombreJours();
                    $montantRetenue = $salaireJournaliere * $nombreJours;
                    $retenues[] = [
                        'type' => $sanction->getTypeSanction()->getNom(),
                        'montantRetenue' => round($montantRetenue, 2),
                        'details' => $sanction->getNombreJours() . ' jours',
                    ];
                } elseif ($sanction->getTypeSanction()->getNom() === 'mis a pied') {
                    $dateDebut = $sanction->getDateDebut();
                    $dateFin = $sanction->getDateFin();
                    $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                    $montantRetenue = $salaireJournaliere * $nombreJours;
                    $retenues[] = [
                        'type' => $sanction->getTypeSanction()->getNom(),
                        'montantRetenue' => round($montantRetenue, 2),
                        'details' => 'Du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y'),
                    ];
                }
            }

            $bulletins[] = [
                'employe' => $employe,
                'salaireBase' => $salaireDeBase,
                'prime' => $primes,
                'heureSup' => $heureSup,
                'retenues' => $retenues,
            ];
        }
        return $this->render('paie/bulletin.html.twig', [
            'bulletins' => $bulletins,
        ]);
    }


    /**
     * @Route("/new", name="paie_new", methods={"POST","GET"})
     */
    public function new(Request $request, PrimeRepository $primeRepository, HeureSuplementaireRepository $heureSuplementaireRepository, RetenueRepository $retenueRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        // Récupération du salaire de base depuis le poste

        $employes = $entityManager->getRepository(Employe::class)->findAll();
        foreach ($employes as $employe) {
            $salaireDeBase = $employe->getPoste()->getSalaire();
            $salaireJournaliere = $salaireDeBase / 30;

            //Recuperations des Primes et Heure Supplementaire
            $primes = $entityManager->getRepository(Prime::class)->findBy(['employe' => $employe]);
            $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findBy(['employe' => $employe]);
            $sanctions = $entityManager->getRepository(Sanction::class)->findBy(['employe' => $employe]);

            //Calcul des retenues
            $totalRetenue = 0;
            $RetenueDetails[] = [];

            foreach ($sanctions as $sanction) {
                if ($sanction->getTypeSanction()->getNom() === 'ponction salarial') {
                    $nombreJours = $sanction->getNombreJours();
                    $montantRetenue = $salaireJournaliere * $nombreJours;
                    $totalRetenue += $montantRetenue;

                    $detailsRetenues[] = [
                        'type' => 'Ponction salariale',
                        'montant' => round($montantRetenue, 2),
                        'details' => $nombreJours . ' jours de retenue',
                    ];

                } elseif ($sanction->getTypeSanction()->getNom() === 'mis a pied') {
                    $dateDebut = $sanction->getDateDebut();
                    $dateFin = $sanction->getDateFin();
                    $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                    $montantRetenue = $salaireJournaliere * $nombreJours;
                    $totalRetenue += $montantRetenue;

                    $detailsRetenues[] = [
                        'type' => 'Mise à pied',
                        'montant' => round($montantRetenue, 2),
                        'details' => 'Du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y'),
                    ];

                }
            }

            $totalPrimes = $primeRepository->getTotalPrimesByEmploye($employe);
            $totalHeureSup = $heureSuplementaireRepository->getTotalHeuresByEmploye($employe);
            $salaireNet = $salaireDeBase + $totalHeureSup + $totalPrimes - $totalRetenue;

            // Enregistrement dans la table paie
            $paie = new Paie();
            $paie->setSalaireBase($salaireDeBase);
            $paie->setEmploye($employe);
            $paie->setMois(new \DateTime());
            $paie->setTotalPrime($totalPrimes);
            $paie->setTotalheureSup($totalHeureSup);
            $paie->setTotalRetenue($totalRetenue);
            $paie->setSalaireNet($salaireNet);
            $entityManager->persist($paie);

        }

        $entityManager->flush();
        return $this->redirectToRoute('paie_historique');
    }

    /**
     * @Route("/Historique", name="paie_historique", methods={"GET"})
     */
    public function historique(PaieRepository $paieRepository): Response
    {
        $paie = $paieRepository->findAll();
        return $this->render('paie/historique.html.twig', [
            'paie' => $paie,
        ]);
    }


    /**
     * @Route("/{id}", name="paie_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $entityManager->getRepository(Employe::class)->find($id);


        $primes = $entityManager->getRepository(Prime::class)->findBy(['employe' => $employe]);
        $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findBy(['employe' => $employe]);

        return $this->render('paie/show.html.twig', [
            'employe' => $employe,
            'primes' => $primes,
            'heureSup' => $heureSup,
        ]);
    }

}
