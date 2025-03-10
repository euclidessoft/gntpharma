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
            $employes = $entityManager->getRepository(Employe::class)->findAll();
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
                        'prime' => $primes,
                        'heureSup' => $heureSup,
                    ];
                }
            }
            return $this->render('paie/admin/index.html.twig', [
                'paie' => $paies,
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
            $entityManager = $this->getDoctrine()->getManager();
            $startOfMonth = new \DateTime('01-' . date('m') . '-' . date('Y'));
            $endOfMonth = new \DateTime('last day of this month');
            $employes = $entityManager->getRepository(Employe::class)->findAll();
            $bulletins = [];

            foreach ($employes as $employe) {

                $paieExistante = $entityManager->getRepository(Paie::class)->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
                if ($paieExistante) {
                    continue;
                }
                $salaireDeBase = $employe->getPoste()->getSalaire();
                $salaireJournaliere = $salaireDeBase / 30;

                $primes = $entityManager->getRepository(Prime::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

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
                    'mois' => $startOfMonth,
                    'salaireBase' => $salaireDeBase,
                    'primes' => $primes,
                    'heureSup' => $heureSup,
                    'retenues' => $retenues,
                ];
            }
            //dd($employe,$startOfMonth,$salaireDeBase,$primes,$retenues);
            return $this->render('paie/admin/bulletin.html.twig', [
                'bulletins' => $bulletins,
                'paieExistante' => $paieExistante
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
//        $bulletins = $this->paieService->bulletin();
//
//        //dd($employe,$startOfMonth,$salaireDeBase,$primes,$retenues);
//        return $this->render('paie/admin/bulletin.html.twig', [
//            'bulletins' => $bulletins,
//        ]);
    }


    /**
     * @Route("/Imprimer/Bulletin", name="print_bulletin")
     */
    public function printBulletin(): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $startOfMonth = new \DateTime('01-' . date('m') . '-' . date('Y'));
            $endOfMonth = new \DateTime('last day of this month');
            $employes = $entityManager->getRepository(Employe::class)->findAll();
            $bulletins = [];

            foreach ($employes as $employe) {

                $paieExistante = $entityManager->getRepository(Paie::class)->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
                if ($paieExistante) {
                    continue;
                }
                $salaireDeBase = $employe->getPoste()->getSalaire();
                $salaireJournaliere = $salaireDeBase / 30;

                $primes = $entityManager->getRepository(Prime::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
                $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

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
                    'mois' => $startOfMonth,
                    'salaireBase' => $salaireDeBase,
                    'primes' => $primes,
                    'heureSup' => $heureSup,
                    'retenues' => $retenues,
                ];
            }
            //dd($employe,$startOfMonth,$salaireDeBase,$primes,$retenues);
            return $this->render('paie/admin/bulletin_print.html.twig', [
                'bulletins' => $bulletins,
                'paieExistante' => $paieExistante
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
//       $bulletins = $this->paieService->bulletin();
//        return $this->render('paie/admin/bulletin_print.html.twig', [
//            'bulletins' => $bulletins,
//        ]);
    }

    /**
     * @Route("/new", name="paie_new", methods={"POST","GET"})
     */
    public function new(Request $request, PrimeRepository $primeRepository, HeureSuplementaireRepository $heureSuplementaireRepository, RetenueRepository $retenueRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $startOfMonth = new \DateTime('01-' . date('m') . '-' . date('Y'));
            $endOfMonth = new \DateTime('last day of this month');

            $employes = $entityManager->getRepository(Employe::class)->findAll();
            foreach ($employes as $employe) {
                $salaireDeBase = $employe->getPoste()->getSalaire();
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
                $paie->setDetailsRetenues(json_encode($detailsRetenues));
                $entityManager->persist($paie);
            }

            $entityManager->flush();
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
     * @Route("/Historique", name="paie_historique", methods={"GET"})
     */
    public function historique(PaieRepository $paieRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $paie = $paieRepository->findAll();
            return $this->render('paie/admin/historique.html.twig', [
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
    public function show(int $id): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $startOfMonth = new \DateTime('01-' . date('m') . ('-') . date('Y'));
            $endOfMonth = new \DateTime('last day of this month');
            $employe = $entityManager->getRepository(Employe::class)->find($id);

            // Vérifier si la paie du mois en cours est déjà validée
            $paieExistante = $entityManager->getRepository(Paie::class)->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
            $primes = $entityManager->getRepository(Prime::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

            $retenues = [];
            $salaireJournalier = $employe->getPoste()->getSalaire() / 30; // Salaire journalier
            foreach ($sanctions as $sanction) {
                $montantRetenue = 0;
                if ($sanction->getTypeSanction()->getNom() === 'ponction salarial') {
                    $nombreJours = $sanction->getNombreJours();
                    $montantRetenue = $salaireJournalier * $nombreJours;
                } elseif ($sanction->getTypeSanction()->getNom() === 'mis a pied') {
                    $dateDebut = $sanction->getDateDebut();
                    $dateFin = $sanction->getDateFin();
                    $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                    $montantRetenue = $salaireJournalier * $nombreJours;
                }

                $retenues[] = [
                    'type' => $sanction->getTypeSanction()->getNom(),
                    'montantRetenue' => round($montantRetenue, 2),
                    'details' => isset($nombreJours) ? "{$nombreJours} jours" : 'Période inconnue',
                ];
            }


            return $this->render('paie/admin/show.html.twig', [
                'employe' => $employe,
                'primes' => $primes,
                'heureSup' => $heureSup,
                'retenues' => $retenues,
                'paieExistante' => $paieExistante,
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
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $entityManager->getRepository(Employe::class)->find($id);

            $salaireDeBase = $employe->getPoste()->getSalaire();
            $salaireJournaliere = $salaireDeBase / 30; // Calcul du salaire journalier (par défaut 30 jours)

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
            // Calcul du salaire net
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
            $paie->setDetailsRetenues(json_encode($detailsRetenues));
            $entityManager->persist($paie);
            $entityManager->flush();
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
//        $entityManager->flush();
//        return $this->redirectToRoute('print_bulletin');
    }

    /**
     * @Route("/Historique", name="paie_historique", methods={"GET"})
     */
    public function historique(PaieRepository $paieRepository): Response
    {
        $paie = $paieRepository->findAll();
        return $this->render('paie/admin/historique.html.twig', [
            'paie' => $paie,
        ]);
    }

    /**
     * @Route("/Historique/Bulletin", name="paie_historique_bulletin", methods={"GET"})
     */
    public function historiqueBulletin(PaieRepository $paieRepository): Response
    {
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
    }

    /**
     * @Route("/Historique/{id}", name="paie_historique_show", methods={"GET"})
     */
    public function historiqueShow(Paie $paie): Response
    {
        $detailsRetenues = json_decode($paie->getDetailsRetenues(), true); // Si tu as stocké en JSON, décode-le en tableau associatif

        return $this->render('paie/admin/historique_show.html.twig', [
            'paie' => $paie,
            'detailsRetenues' => $detailsRetenues,
        ]);
    }

    /**
     * @Route("Details/{id}", name="paie_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $startOfMonth = new \DateTime('01-' . date('m') . ('-') . date('Y'));
        $endOfMonth = new \DateTime('last day of this month');
        $employe = $entityManager->getRepository(Employe::class)->find($id);

        // Vérifier si la paie du mois en cours est déjà validée
        $paieExistante = $entityManager->getRepository(Paie::class)->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
        $primes = $entityManager->getRepository(Prime::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
        $heureSup = $entityManager->getRepository(HeureSuplementaire::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
        $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

        $retenues = [];
        $salaireJournalier = $employe->getPoste()->getSalaire() / 30; // Salaire journalier
        foreach ($sanctions as $sanction) {
            $montantRetenue = 0;
            if ($sanction->getTypeSanction()->getNom() === 'ponction salarial') {
                $nombreJours = $sanction->getNombreJours();
                $montantRetenue = $salaireJournalier * $nombreJours;
            } elseif ($sanction->getTypeSanction()->getNom() === 'mis a pied') {
                $dateDebut = $sanction->getDateDebut();
                $dateFin = $sanction->getDateFin();
                $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                $montantRetenue = $salaireJournalier * $nombreJours;
            }

            $retenues[] = [
                'type' => $sanction->getTypeSanction()->getNom(),
                'montantRetenue' => round($montantRetenue, 2),
                'details' => isset($nombreJours) ? "{$nombreJours} jours" : 'Période inconnue',
            ];
        }


        return $this->render('paie/admin/show.html.twig', [
            'employe' => $employe,
            'primes' => $primes,
            'heureSup' => $heureSup,
            'retenues' => $retenues,
            'paieExistante' => $paieExistante,
        ]);
    }

    /**
     * @Route("/valider/{id}", name="paie_valider", methods={"POST","GET"})
     */
    public function valider(int $id, PrimeRepository $primeRepository, HeureSuplementaireRepository $heureSuplementaireRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $entityManager->getRepository(Employe::class)->find($id);

        $salaireDeBase = $employe->getPoste()->getSalaire();
        $salaireJournaliere = $salaireDeBase / 30; // Calcul du salaire journalier (par défaut 30 jours)

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
        // Calcul du salaire net
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
        $paie->setDetailsRetenues(json_encode($detailsRetenues));
        $entityManager->persist($paie);
        $entityManager->flush();
        return $this->redirectToRoute('paie_historique');
    }


    /**
     * @Route("/Paiement/", name="mes_bulletins", methods={"GET"})
     */
    public function paiement(Security $security): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $bulletin = $entityManager->getRepository(Paie::class)->findBy(['employe' => $employe]);
            return $this->render("paie/index.html.twig", [
                'bulletins' => $bulletin,
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
     * @Route("/Paiement/Details/{id}", name="mes_bulletin_details", methods={"GET"})
     */
    public function paimentDetails(Paie $paie): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $detailsRetenues = json_decode($paie->getDetailsRetenues(), true); // Si tu as stocké en JSON, décode-le en tableau associatif

            return $this->render("paie/detail_bulletin.html.twig", [
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
}