<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\HeureSuplementaire;
use App\Entity\Mois;
use App\Entity\Paie;
use App\Entity\Prime;
use App\Entity\PrimePerformance;
use App\Entity\Sanction;
use App\Form\FiltreBulletinType;
use App\Form\PaieType;
use App\Repository\HeureSuplementaireRepository;
use App\Repository\PaieRepository;
use App\Repository\PrimeRepository;
use App\Repository\RetenueRepository;
use App\Service\PaieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/Paie")
 */
class PaieController extends AbstractController
{
    private $paieService;
    public function __construct(PaieService $paieService)
    {
        $this->paieService = $paieService;
    }

    /**
     * @Route("/", name="paie_index")
     */
    public function index(): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employes = $entityManager->getRepository(Employe::class)->findBy(['status' => true]);
            $paies = [];
            $startOfMonth = new \DateTime('01-' . date('m') . '-' . date('Y'));
            $endOfMonth = new \DateTime('last day of this month');
            foreach ($employes as $employe) {
                //On verifie si le bulletin est deja enregistrer
                $bulletinExist = $entityManager->getRepository(Paie::class)->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
                if (!$bulletinExist) {
                    //Recuperations des Primes et Heure Supplementaire
                    $primes = $entityManager->getRepository(Prime::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                    $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                    $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                    $salaireDeBase = $employe->getPoste()->getSalaire();

                    $paies[] = [
                        'employe' => $employe,
                        'salaireBase' => $salaireDeBase,
                        'salaireBrut' => $salaireDeBase + $employe->getSursalaire(),
                        'salaireNet' => $salaireDeBase + $employe->getSursalaire() ,
                        'prime' => $primes,
                        'heureSup' => $heureSup,
                    ];
                }
            }
            return $this->render('paie/admin/index.html.twig', [
                'paies' => $paies,
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
     * @Route("/Bulletin", name="paie_bulletin", methods={"GET"})
     */
    public function bulletin(): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $bulletins = $this->paieService->bulletin();

            //dd($employe,$startOfMonth,$salaireDeBase,$primes,$retenues);
            return $this->render('paie/admin/bulletin.html.twig', [
                'bulletins' => $bulletins,
                'mois' => $this->getDoctrine()->getRepository(Mois::class)->find(date('m')),
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
     * @Route("/Imprimer/Bulletin", name="print_bulletin")
     */
    public function printBulletin(): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $bulletins = $this->paieService->bulletin();
            return $this->render('paie/admin/bulletin_print.html.twig', [
                'bulletins' => $bulletins,
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
     * @Route("/new", name="paie_new", methods={"POST","GET"})
     */
    public function new(Request $request, PrimeRepository $primeRepository, HeureSuplementaireRepository $heureSuplementaireRepository, RetenueRepository $retenueRepository): Response
    {// validation de tous les buletins de salaire
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $startOfMonth = new \DateTime('01-' . date('m') . '-' . date('Y'));
            $endOfMonth = new \DateTime('last day of this month');
            $mois = $entityManager->getRepository(Mois::class)->find(date('m'));

        $employes = $entityManager->getRepository(Employe::class)->findBy(['status' => true]);
        foreach ($employes as $employe) {
            $paieExistante = $entityManager->getRepository(Paie::class)->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
            if ($paieExistante) {
                continue;
            }

            $salaireDeBase = $employe->getPoste()->getSalaire() + $employe->getSursalaire();
            $salaireJournaliere = $salaireDeBase / 30;

                $primes = $entityManager->getRepository(Prime::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

                $totalRetenue = 0;
                $detailsRetenues = [];

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
                $salaireBrut = $salaireDeBase + $totalHeureSup + $totalPrimes - $totalRetenue;
                $chargeSocial = 14000;
                $chargePatronal= 7000;
                $salaireNetImposable = $salaireBrut - $chargeSocial;
                $impot = 8000;
                $salaireNet = $salaireNetImposable - $impot;
                // Enregistrement dans la table paie
                $paie = new Paie();
                $paie->setSalaireBase($salaireDeBase);
                $paie->setEmploye($employe);
                $paie->setMois($mois);
                $paie->setTotalPrime($totalPrimes);
                $paie->setTotalheureSup($totalHeureSup);
                $paie->setTotalRetenue($totalRetenue);
                $paie->setSalaireBrut($salaireBrut);
                $paie->setSalaireNetImposable($salaireNetImposable);
                $paie->setTotalChargeEmploye($chargeSocial);
                $paie->setTotalChargePatronal($chargePatronal);
                $paie->setSalaireNet($salaireNet);
                $paie->setImpot($impot);
                $paie->setDetailsRetenues(json_encode($detailsRetenues));
                $entityManager->persist($paie);
            }



        $entityManager->flush();
        $this->addFlash('notice', 'Bulletin validé qvec succès');
        return $this->redirectToRoute('print_bulletin');
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
     * @Route("/Historique", name="paie_historique", methods={"GET","POST"})
     */
    public function historique(Request $request, PaieRepository $paieRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $form = $this->createForm(FiltreBulletinType::class);
            $form->handleRequest($request);
            $paie = [];


            if ($form->isSubmitted() && $form->isValid()) {
                $filters = $form->getData();
                $paie = $paieRepository->findByFiltrer(
                    $filters['employe'] ?? null,
                    $filters['mois'] ?? null,
                    $filters['annee'] ?? null
                );
                return $this->render('paie/admin/historique.html.twig', [
                    'form' => $form->createView(),
                    'paie' => $paie,
                ]);
            }
            $paie = $paieRepository->findAll();
            return $this->render('paie/admin/historique.html.twig', [
                'form' => $form->createView(),
                'paie' => $paie,
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
     * @Route("/HistoriqueMois", name="paie_historique_mois_en_cours", methods={"GET"})
     */
    public function historiqueMonthCurent(PaieRepository $paieRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
        $paie = $paieRepository->findPaieCurrentMonth();
            return $this->render('paie/admin/historique_mois_encours.html.twig', [
                'paie' => $paie,
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
     * @Route("/Historique/Bulletin", name="paie_historique_bulletin", methods={"GET"})
     */
    public function historiqueBulletin(PaieRepository $paieRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $paie = $paieRepository->findAll();
            $detailsRetenues = [];

            foreach ($paie as $singlePaie) {
                // Si 'detailsRetenues' existe et n'est pas vide
                $details = json_decode($singlePaie->getDetailsRetenues(), true);

                // Si le JSON est valide et contient des éléments
                if (is_array($details) && count($details) > 0) {
                    $detailsRetenues[] = $details;
                } else {
                    // Ajouter un tableau vide si aucune retenue
                    $detailsRetenues[] = [];
                }
            }

            return $this->render('paie/admin/historique_bulletin.html.twig', [
                'paie' => $paie,
                'detailsRetenues' => $detailsRetenues,
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
     * @Route("/Historique/{id}", name="paie_historique_show", methods={"GET"})
     */
    public function historiqueShow(Paie $paie): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $detailsRetenues = json_decode($paie->getDetailsRetenues(), true); // Si tu as stocké en JSON, décode-le en tableau associatif

            return $this->render('paie/admin/historique_show.html.twig', [
                'paie' => $paie,
                'detailsRetenues' => $detailsRetenues,
                'mois' => $this->getDoctrine()->getRepository(Mois::class)->find(date('m')),
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
     * @Route("Details/{id}", name="paie_show", methods={"GET"})
     */
    public function show(int $id, PrimeRepository $primeRepository, HeureSuplementaireRepository $heureSuplementaireRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $startOfMonth = new \DateTime('01-' . date('m') . ('-') . date('Y'));
            $endOfMonth = new \DateTime('last day of this month');
            $employe = $entityManager->getRepository(Employe::class)->find($id);
            $mois = $entityManager->getRepository(Mois::class)->find(date('m'));

            // Vérifier si la paie du mois en cours est déjà validée
            $paieExistante = $entityManager->getRepository(Paie::class)->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
//            $primes = $entityManager->getRepository(Prime::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
//            $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

            $primes = $entityManager->getRepository(Prime::class)->findBy(['employe' => $employe->getId()]);

            $heureSups = $heureSuplementaireRepository->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $nombreHeures = 0;
            foreach ($heureSups as $heureSup) {
                // calcul nombre d'heure
                $nombreHeures = $nombreHeures + $heureSup->getDuree();
            }


            $primeperformances = $entityManager->getRepository(PrimePerformance::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $totalPrimePerf = 0;
            foreach ($primeperformances as $primeP) {
                // calcul nombre d'heure
                $totalPrimePerf = $totalPrimePerf + $primeP->getMontant();
            }


            $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

            $retenues = [];
            $nombreJours = 0;
            $salaireJournalier = $employe->getPoste()->getSalaire() / 30; // Salaire journalier
//            $montantRetenue = 0;
            foreach ($sanctions as $sanction) {
                // calcul nombre jours

                if ($sanction->getTypeSanction()->getNom() === 'Ponction Salariale') {
                    $nombreJours =  $nombreJours + $sanction->getNombreJours();
//                    $montantRetenue = $montantRetenue + $salaireJournalier * $nombreJours;
                } elseif ($sanction->getTypeSanction()->getNom() === 'Mis a pied') {
                    $dateDebut = $sanction->getDateDebut();
                    $dateFin = $sanction->getDateFin();
                    $nombreJours = $nombreJours + $dateDebut->diff($dateFin)->days + 1;
//                    $montantRetenue = $montantRetenue + $salaireJournalier * $nombreJours;
                }

                $retenues[] = [
//                    'type' => $sanction->getTypeSanction()->getNom(),
                    'montant' => $salaireJournalier,
                    'jours' => $nombreJours,//isset($nombreJours) ? "{$nombreJours}" : 'Période inconnue',
                ];
            }


            return $this->render('paie/admin/show.html.twig', [
                'employe' => $employe,
                'primes' => $primes,
                'totalPrimePerf' => $totalPrimePerf,
                'heureSups' => $nombreHeures,
                'retenues' => $retenues,
                'paieExistante' => $paieExistante,
                'mois' => $mois,
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
     * @Route("/valider/{id}", name="paie_valider", methods={"POST","GET"})
     */
    public function valider(int $id, PrimeRepository $primeRepository, HeureSuplementaireRepository $heureSuplementaireRepository): Response
    {// validation d'un seul buletin de salaire
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $entityManager->getRepository(Employe::class)->find($id);
            $mois = $entityManager->getRepository(Mois::class)->find(date('m'));

            $salaireDeBase = $employe->getPoste()->getSalaire() + $employe->getSursalaire();
            $salaireJournaliere = $salaireDeBase / 30; // Calcul du salaire journalier (par défaut 30 jours)
            //$impot = $salaireDeBase * 0.01;

            $totalRetenue = 0;
            $detailsRetenues = [];

            $sanctions = $entityManager->getRepository(Sanction::class)->findBy(['employe' => $employe]);

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

            // Calcul des primes et heures supplémentaires
            $totalPrimes = $primeRepository->getTotalPrimesByEmploye($employe);
            $totalHeureSup = $heureSuplementaireRepository->getTotalHeuresByEmploye($employe);
            $salaireBrut = $salaireDeBase + $totalHeureSup + $totalPrimes - $totalRetenue;
            $chargeSocial = 14000;
            $chargePatronal= 7000;
            $salaireNetImposable = $salaireBrut - $chargeSocial;
            $impot = 8000;
            $salaireNet = $salaireNetImposable - $impot;
            // Enregistrement dans la table paie
            $paie = new Paie();
            $paie->setSalaireBase($salaireDeBase);
            $paie->setEmploye($employe);
            $paie->setMois($mois);
            $paie->setTotalPrime($totalPrimes);
            $paie->setTotalheureSup($totalHeureSup);
            $paie->setTotalRetenue($totalRetenue);
            $paie->setSalaireBrut($salaireBrut);
            $paie->setSalaireNetImposable($salaireNetImposable);
            $paie->setTotalChargeEmploye($chargeSocial);
            $paie->setTotalChargePatronal($chargePatronal);
            $paie->setSalaireNet($salaireNet);
            $paie->setImpot($impot);
            $paie->setDetailsRetenues(json_encode($detailsRetenues));
            $entityManager->persist($paie);
            $entityManager->flush();
            $this->addFlash('notice', 'Bulletin validé qvec succès');
            return $this->redirectToRoute('paie_historique');
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
     * @Route("/Paiement/", name="mes_bulletins", methods={"GET","POST"})
     */
    public function paiement(Security $security, Request $request, PaieRepository $paieRepository): Response
    {
        if ($this->getUser() !== null) {
            $entityManager = $this->getDoctrine()->getManager();
//            $mois = $entityManager->getRepository(Mois::class)->find(date('m'));
            $employe = $security->getUser();

            $form = $this->createForm(FiltreBulletinType::class);
            $form->remove('employe');
            $form->handleRequest($request);
            $paie = [];

            if ($form->isSubmitted() && $form->isValid()) {
                $filters = $form->getData();
                $paie = $paieRepository->findByFiltrer(
                    $filters['employe'] ?? null,
                    $filters['mois'] ?? null,
                    $filters['annee'] ?? null
                );
                return $this->render('paie/index.html.twig', [
                    'form' => $form->createView(),
                    'bulletins' => $paie,
                ]);
            }
            $bulletin = $entityManager->getRepository(Paie::class)->findBy(['employe' => $employe]);

            $response = $this->render("paie/index.html.twig", [
                'bulletins' => $bulletin,
                'form' => $form->createView(),
//                'mois' => $mois,
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
     * @Route("/Paiement/Details/{id}", name="mes_bulletin_details", methods={"GET"})
     */
    public function paimentDetails(Paie $paie): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $detailsRetenues = json_decode($paie->getDetailsRetenues(), true); // Si tu as stocké en JSON, décode-le en tableau associatif

            return $this->render("paie/detail_bulletin.html.twig", [
                'paie' => $paie,
                'detailsRetenues' => $detailsRetenues,
                'mois' => $this->getDoctrine()->getRepository(Mois::class)->find(date('m')),
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
}
