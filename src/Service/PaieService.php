<?php

namespace App\Service;

use App\Entity\Employe;
use App\Entity\HeureSuplementaire;
use App\Entity\Paie;
use App\Entity\Prime;
use App\Entity\Sanction;
use App\Repository\HeureSuplementaireRepository;
use App\Repository\PaieRepository;
use App\Repository\PrimeRepository;
use App\Repository\SanctionRepository;
use Doctrine\ORM\EntityManagerInterface;

class PaieService
{
    private $paieRepository;
    private $primeRepository;
    private $heureSupRepository;
    private $sanctionRepository;
    private $entityManager;

    public function __construct(PaieRepository $paieRepository,
                                PrimeRepository $primeRepository,
                                HeureSuplementaireRepository $heureSupRepository,
                                SanctionRepository $sanctionRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->paieRepository = $paieRepository;
        $this->primeRepository = $primeRepository;
        $this->heureSupRepository = $heureSupRepository;
        $this->sanctionRepository = $sanctionRepository;
        $this->entityManager = $entityManager;
    }

    public function bulletin(): array
    {
        // Calcul du début et de la fin du mois
        $startOfMonth = new \DateTime('first day of this month');
        $endOfMonth = new \DateTime('last day of this month');
        $employes = $this->entityManager->getRepository(Employe::class)->findAll();
        $bulletins = [];

        foreach ($employes as $employe) {
            // Vérification si la paie existe déjà pour le mois en cours
            $paieExistante = $this->paieRepository->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
            if ($paieExistante) {
                continue;
            }

            // Récupération du salaire de base
            $salaireDeBase = $employe->getPoste()->getSalaire() + $employe->getSursalaire();
            $salaireJournaliere = $salaireDeBase / 30;

            // Récupération des primes, heures supplémentaires et sanctions pour le mois en cours
            $primes = $this->primeRepository->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $heureSup = $this->heureSupRepository->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $sanctions = $this->sanctionRepository->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

            // Calcul des retenues
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

            // Création du bulletin de paie pour l'employé
            $bulletins[] = [
                'employe' => $employe,
                'mois' => $startOfMonth->format('F Y'),
                'salaireBase' => $salaireDeBase,
                'primes' => $primes,
                'heureSup' => $heureSup,
                'retenues' => $retenues,
            ];
        }

        return $bulletins;
    }

    /**
     * Calcul du montant de la retenue pour une sanction
     */

    public function calculeRetenue(Sanction $sanction, float $salaireJournaliere)
    {
        $montantRetenue = 0;
        $retenues = [];
        if ($sanction->getTypeSanction()->getNom() === 'ponction salarial') {
            $nombreJours = $sanction->getNombreJours();
            $montantRetenue = $salaireJournaliere * $nombreJours;
        } elseif ($sanction->getTypeSanction()->getNom() === 'mis a pied') {
            $dateDebut = $sanction->getDateDebut();
            $dateFin = $sanction->getDateFin();
            $nombreJours = $dateDebut->diff($dateFin)->days + 1;
            $montantRetenue = $salaireJournaliere * $nombreJours;
        }

        $retenues[] = [
            'type' => $sanction->getTypeSanction()->getNom(),
            'montantRetenue' => round($montantRetenue, 2),
            'details' => isset($nombreJours) ? "{$nombreJours} jours" : 'Période inconnue',
        ];

        return $retenues;
    }

}
