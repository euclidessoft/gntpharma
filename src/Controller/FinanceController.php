<?php

namespace App\Controller;

use App\Complement\Solde;
use App\Entity\Banque;
use App\Entity\Debit;
use App\Entity\Depense;
use App\Entity\Ecriture;
use App\Entity\Paie;
use App\Entity\PaieSalaire;
use App\Repository\EcritureRepository;
use App\Repository\PaieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/{_locale}/finance", name="finance_")
 */
class FinanceController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EcritureRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->findAll();
            $caisse = 0;
            $banque = 0;
            $debitbanque = 0;
            $debitcaisse = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisse = $caisse + $credit->getMontant() :
                        $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisse = $debitcaisse + $debit->getMontant() :
                        $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            return $this->render('finance/index.html.twig', [
                'caisse' => $caisse - $debitcaisse,
                'banque' => $banque - $debitbanque,
                'ecritures' => $ecritures,
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
     * @Route("/JournalBanque/{banque}", name="journal_banque")
     */
    public function journalbanque(EcritureRepository $repository, Banque $banque, Solde $solde): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->findAll();

            $caisse = 0;
            $bank = 0;
            $debitbanque = 0;
            $debitcaisse = 0;
            $ecrit = [];
            foreach ($ecritures as $ecriture) {
                if ($banque->getCompte() == $ecriture->getComptecredit() || $banque->getCompte() == $ecriture->getComptedebit()) {
                    $credit = null;
                    $debit = null;
                    if ($ecriture->getCredit() != null) {
                        $credit = $ecriture->getCredit();

                        $bank = $bank + $credit->getMontant();
                    } else {

                        $debit = $ecriture->getDebit();

                        $debitbanque = $debitbanque + $debit->getMontant();

                    }
                    $ecrit[] = $ecriture;
                }

            }

            return $this->render('banque/journal_banque.html.twig', [
                'soldecaisse' => $caisse - $debitcaisse,
                'soldebanque' => $bank - $debitbanque,
                'banque' => $banque,
                'ecritures' => $ecrit,
                'solde' => $solde->montantbanque($this->getDoctrine()->getManager(), $banque->getCompte()),
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
     * @Route("/JournalCompte/{compte}", name="journal_compte")
     */
    public function journalCompte(EcritureRepository $repository, $compte): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->findAll();

            $caisse = 0;
            $bank = 0;
            $debitbanque = 0;
            $debitcaisse = 0;
            $ecrit = [];
            foreach ($ecritures as $ecriture) {
                if ($compte == $ecriture->getComptecredit() || $compte == $ecriture->getComptedebit()) {
                    $credit = null;
                    $debit = null;
                    if ($ecriture->getCredit() != null) {
                        $credit = $ecriture->getCredit();

                        $bank = $bank + $credit->getMontant();
                    } else {

                        $debit = $ecriture->getDebit();

                        $debitbanque = $debitbanque + $debit->getMontant();

                    }
                    $ecrit[] = $ecriture;
                }

            }

            return $this->render('finance/journal_compte.html.twig', [
                'caisse' => $caisse - $debitcaisse,
                'banque' => $bank - $debitbanque,
                'ecritures' => $ecrit,
                'compte' => $compte,
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
     * @Route("/Brouillard", name="brouyard")
     */
    public function brouyard(EcritureRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->brouyard();
            $ouverture = $repository->ouverture();
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisse = $caisse + $credit->getMontant() :
                        $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisse = $debitcaisse + $debit->getMontant() :
                        $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisseouv = $caisseouv + $credit->getMontant() :
                        $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisseouv = $debitcaisseouv + $debit->getMontant() :
                        $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => ($caisseouv - $debitcaisseouv) + ($banqueouv - $debitbanqueouv)
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
     * @Route("/Brouillard_print", name="brouyard_print")
     */
    public function brouyard_print(EcritureRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->brouyard();
            $ouverture = $repository->ouverture();
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisse = $caisse + $credit->getMontant() :
                        $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisse = $debitcaisse + $debit->getMontant() :
                        $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisseouv = $caisseouv + $credit->getMontant() :
                        $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisseouv = $debitcaisseouv + $debit->getMontant() :
                        $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_print.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => ($caisseouv - $debitcaisseouv) + ($banqueouv - $debitbanqueouv)
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
     * @Route("/BrouillardCaisse", name="brouyard_caisse")
     */
    public function brouyardCaisse(EcritureRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->brouyardcaisse();
            $ouverture = $repository->ouverturecaisse();
            $caisse = 0;
            $caisseouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisse = $caisse + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisse = $debitcaisse + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisseouv = $caisseouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisseouv = $debitcaisseouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_caisse.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'ecritures' => $ecritures,
                'ouverture' => $caisseouv - $debitcaisseouv,
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
     * @Route("/BrouillardCaisse_print", name="brouyard_caisse_print")
     */
    public function brouyardCaisse_print(EcritureRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->brouyardcaisse();
            $ouverture = $repository->ouverturecaisse();
            $caisse = 0;
            $caisseouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisse = $caisse + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisse = $debitcaisse + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisseouv = $caisseouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisseouv = $debitcaisseouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_caisse_print.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'ecritures' => $ecritures,
                'ouverture' => $caisseouv - $debitcaisseouv,
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
     * @Route("/BrouillardBanque", name="brouyard_banque")
     */
    public function brouyardBanque(EcritureRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->brouyardbanque();
            $ouverture = $repository->ouverturebanque();
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();

                    $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();

                    $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_banque.html.twig', [
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => $banqueouv - $debitbanqueouv,
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
     * @Route("/BrouillardBanque_print", name="brouyard_banque_print")
     */
    public function brouyardBanque_print(EcritureRepository $repository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $ecritures = $repository->brouyardbanque();
            $ouverture = $repository->ouverturebanque();
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();

                    $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();

                    $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_banque_print.html.twig', [
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => $banqueouv - $debitbanqueouv,
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
     * @Route("/LienDayBrouyard", name="day_brouyard_lien")
     */
    public function liendaybrouyard(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $date1 = $request->get('date1');
            $lien = $this->generateUrl('finance_day_brouyard', ['jour' => $date1]);
            $res['ok'] = $lien;
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/DayRepport/", name="rapport_date")
     */
    public function rapportdate()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            return $this->render('finance/rapport_date.html.twig');
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
     * @Route("/DayBrouyard/{jour}", name="day_brouyard")
     */
    public function daybrouyard(Request $request, $jour)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->daybrouyard($jour);
            $ouverture = $repository->ouvertureplace($jour);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisse = $caisse + $credit->getMontant() :
                        $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisse = $debitcaisse + $debit->getMontant() :
                        $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisseouv = $caisseouv + $credit->getMontant() :
                        $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisseouv = $debitcaisseouv + $debit->getMontant() :
                        $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_date.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => ($caisseouv - $debitcaisseouv) + ($banqueouv - $debitbanqueouv),
                'day' => $jour,
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
     * @Route("/DayBrouyard_print/{jour}", name="day_brouyard_print")
     */
    public function daybrouyard_print(Request $request, $jour)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->daybrouyard($jour);
            $ouverture = $repository->ouvertureplace($jour);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisse = $caisse + $credit->getMontant() :
                        $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisse = $debitcaisse + $debit->getMontant() :
                        $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisseouv = $caisseouv + $credit->getMontant() :
                        $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisseouv = $debitcaisseouv + $debit->getMontant() :
                        $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_date_print.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => ($caisseouv - $debitcaisseouv) + ($banqueouv - $debitbanqueouv),
                'day' => $jour,
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
     * @Route("/LienDayBrouyardCaisse", name="day_brouyard_lien_caisse")
     */
    public function liendaybrouyardaisse(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $date1 = $request->get('date1');
            $lien = $this->generateUrl('finance_day_brouyard_caisse', ['jour' => $date1]);
            $res['ok'] = $lien;
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/DayBrouyardCaisse/{jour}", name="day_brouyard_caisse")
     */
    public function daybrouyardcaisse(Request $request, $jour)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->daybrouyardcaisse($jour);
            $ouverture = $repository->ouvertureplacecaisse($jour);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisse = $caisse + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisse = $debitcaisse + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisseouv = $caisseouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisseouv = $debitcaisseouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_date_caisse.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'ecritures' => $ecritures,
                'ouverture' => $caisseouv - $debitcaisseouv,
                'day' => $jour,
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
     * @Route("/DayBrouyardCaisse_print/{jour}", name="day_brouyard_caisse_print")
     */
    public function daybrouyardcaisse_print(Request $request, $jour)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->daybrouyardcaisse($jour);
            $ouverture = $repository->ouvertureplacecaisse($jour);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisse = $caisse + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisse = $debitcaisse + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisseouv = $caisseouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisseouv = $debitcaisseouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_date_caisse_print.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'ecritures' => $ecritures,
                'ouverture' => $caisseouv - $debitcaisseouv,
                'day' => $jour,
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
     * @Route("/LienDayBrouyardBanque", name="day_brouyard_lien_banque")
     */
    public function liendaybrouyarbanque(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $date1 = $request->get('date1');
            $lien = $this->generateUrl('finance_day_brouyard_banque', ['jour' => $date1]);
            $res['ok'] = $lien;
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/DayBrouyardBanque/{jour}", name="day_brouyard_banque")
     */
    public function daybrouyardbanque(Request $request, $jour)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->daybrouyardbanque($jour);
            $ouverture = $repository->ouvertureplacebanque($jour);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_date_banque.html.twig', [
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => $banqueouv - $debitbanqueouv,
                'day' => $jour,
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
     * @Route("/DayBrouyardBanque_print/{jour}", name="day_brouyard_banque_print")
     */
    public function daybrouyardbanque_print(Request $request, $jour)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->daybrouyardbanque($jour);
            $ouverture = $repository->ouvertureplacebanque($jour);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_date_banque_print.html.twig', [
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => $banqueouv - $debitbanqueouv,
                'day' => $jour,
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
     * @Route("/IntervalRepport/", name="rapport_interval")
     */
    public function rapportinterval()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            return $this->render('finance/rapport_interval.html.twig');
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
     * @Route("/LienDaysBrouyard", name="days_brouyard_lien")
     */
    public function liendaysbrouyard(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $date1 = $request->get('date1');
            $date2 = $request->get('date2');
            $lien = $this->generateUrl('finance_days_brouyard', ['date1' => $date1, 'date2' => $date2]);
            $res['ok'] = $lien;
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/DaysBrouyard/{date1}/{date2}", name="days_brouyard")
     */
    public function daysbrouyard(Request $request, $date1, $date2)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->plage($date1, $date2);
            $ouverture = $repository->ouvertureplace($date1);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisse = $caisse + $credit->getMontant() :
                        $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisse = $debitcaisse + $debit->getMontant() :
                        $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisseouv = $caisseouv + $credit->getMontant() :
                        $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisseouv = $debitcaisseouv + $debit->getMontant() :
                        $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_interval.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => ($caisseouv - $debitcaisseouv) + ($banqueouv - $debitbanqueouv),
                'day1' => $date1,
                'day2' => $date2,
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
     * @Route("/DaysBrouyard_print/{date1}/{date2}", name="days_brouyard_print")
     */
    public function daysbrouyard_print(Request $request, $date1, $date2)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->plage($date1, $date2);
            $ouverture = $repository->ouvertureplace($date1);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisse = $caisse + $credit->getMontant() :
                        $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisse = $debitcaisse + $debit->getMontant() :
                        $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $credit->getType() == 'Espece' ?
                        $caisseouv = $caisseouv + $credit->getMontant() :
                        $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debit->getType() == 'Espece' ?
                        $debitcaisseouv = $debitcaisseouv + $debit->getMontant() :
                        $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_interval_print.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => ($caisseouv - $debitcaisseouv) + ($banqueouv - $debitbanqueouv),
                'day1' => $date1,
                'day2' => $date2,
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
     * @Route("/LienDaysBrouyardCaisse", name="days_brouyard_lien_caisse")
     */
    public function liendaysbrouyardcaisse(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $date1 = $request->get('date1');
            $date2 = $request->get('date2');
            $lien = $this->generateUrl('finance_days_brouyard_caisse', ['date1' => $date1, 'date2' => $date2]);
            $res['ok'] = $lien;
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/DaysBrouyardCaisse/{date1}/{date2}", name="days_brouyard_caisse")
     */
    public function daysbrouyardcaisse(Request $request, $date1, $date2)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->plagecaisse($date1, $date2);
            $ouverture = $repository->ouvertureplacecaisse($date1);
            $caisse = 0;
            $caisseouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisse = $caisse + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisse = $debitcaisse + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisseouv = $caisseouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisseouv = $debitcaisseouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_interval_caisse.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'ecritures' => $ecritures,
                'ouverture' => $caisseouv - $debitcaisseouv,
                'day1' => $date1,
                'day2' => $date2,
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
     * @Route("/DaysBrouyardCaisse_print/{date1}/{date2}", name="days_brouyard_caisse_print")
     */
    public function daysbrouyardcaisse_print(Request $request, $date1, $date2)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->plagecaisse($date1, $date2);
            $ouverture = $repository->ouvertureplacecaisse($date1);
            $caisse = 0;
            $caisseouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisse = $caisse + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisse = $debitcaisse + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $caisseouv = $caisseouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitcaisseouv = $debitcaisseouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_interval_caisse_print.html.twig', [
                'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
                'ecritures' => $ecritures,
                'ouverture' => $caisseouv - $debitcaisseouv,
                'day1' => $date1,
                'day2' => $date2,
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
     * @Route("/LienDaysBrouyardBanque", name="days_brouyard_lien_banque")
     */
    public function liendaysbrouyardbanque(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $date1 = $request->get('date1');
            $date2 = $request->get('date2');
            $lien = $this->generateUrl('finance_days_brouyard_banque', ['date1' => $date1, 'date2' => $date2]);
            $res['ok'] = $lien;
            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/DaysBrouyardBanque/{date1}/{date2}", name="days_brouyard_banque")
     */
    public function daysbrouyardbanque(Request $request, $date1, $date2)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->plagebanque($date1, $date2);
            $ouverture = $repository->ouvertureplacebanque($date1);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_interval_banque.html.twig', [
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => $banqueouv - $debitbanqueouv,
                'day1' => $date1,
                'day2' => $date2,
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
     * @Route("/DaysBrouyardBanque_print/{date1}/{date2}", name="days_brouyard_banque_print")
     */
    public function daysbrouyardbanque_print(Request $request, $date1, $date2)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
            $ecritures = $repository->plagebanque($date1, $date2);
            $ouverture = $repository->ouvertureplacebanque($date1);
            $caisse = 0;
            $caisseouv = 0;
            $banque = 0;
            $banqueouv = 0;
            $debitbanque = 0;
            $debitbanqueouv = 0;
            $debitcaisse = 0;
            $debitcaisseouv = 0;
            foreach ($ecritures as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banque = $banque + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanque = $debitbanque + $debit->getMontant();

                }

            }

            foreach ($ouverture as $ecriture) {
                $credit = null;
                $debit = null;
                if ($ecriture->getCredit() != null) {
                    $credit = $ecriture->getCredit();
                    $banqueouv = $banqueouv + $credit->getMontant();
                } else {

                    $debit = $ecriture->getDebit();
                    $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

                }

            }

            return $this->render('finance/brouyard_interval_banque_print.html.twig', [
                'banque' => $banque - $debitbanque + $banqueouv - $debitbanqueouv,
                'ecritures' => $ecritures,
                'ouverture' => $banqueouv - $debitbanqueouv,
                'day1' => $date1,
                'day2' => $date2,
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
     * @Route("/Salaire", name="salaire", methods={"GET"})
     */
    public function salaire(PaieRepository $paieRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $paie = $paieRepository->findBy(['payer' => false]);
            return $this->render('finance/salaire.html.twig', [
                'paies' => $paie,
                'banques' => $this->getDoctrine()->getRepository(Banque::class)->findAll(),
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
     * @Route("/payer", name="payer", methods={"POST"})
     */
    public function payer(PaieRepository $paieRepository, Solde $solde, Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $debit = new Debit();
            $debitcharge = new Debit();
            $debitimpot = new Debit();
            $ecriture = new Ecriture();
            $ecriturecharge = new Ecriture();
            $ecritureimpot = new Ecriture();
            $entityManager = $this->getDoctrine()->getManager();
            $paie = $paieRepository->find($request->get('paie'));
            $banque = $entityManager->getRepository(Banque::class)->find($request->get('banque'));
            $montant = 0;

            $montant = $solde->montantbanque($entityManager, $banque->getCompte());
            $montantapayer = $paie->getSalaireBrut() + $paie->getTotalChargePatronal();

            if ($montantapayer <= $montant) {
            $paieSalaire = new PaieSalaire();
            $paieSalaire->setUser($this->getUser());
            $paieSalaire->setMontant($paie->getSalaireNet());
            $paieSalaire->setEmploye($paie->getEmploye());
            $paieSalaire->setCompte($banque->getCompte());
            $entityManager->persist($paieSalaire);
            $paie->setPayer(true);
            $paie->setDatepaye(new \DateTime());


            $debit->setCompte($banque->getCompte());
            $debit->setType('Banque');
            $debit->setSalaire($paieSalaire);
            $debit->setMontant($paie->getSalaireNet());

            $debitcharge->setCompte($banque->getCompte());
            $debitcharge->setType('Banque');
            $debitcharge->setSalaire($paieSalaire);
            $debitcharge->setMontant($paie->getTotalChargeEmploye() + $paie->getTotalChargePatronal());

            $debitimpot->setCompte($banque->getCompte());
            $debitimpot->setType('Banque');
            $debitimpot->setSalaire($paieSalaire);
            $debitimpot->setMontant($paie->getImpot());

            $ecriture->setType('Banque');
            $ecriture->setComptecredit("641");
            $ecriture->setLibellecomptecredit("Salaire Personnel");
            $ecriture->setComptedebit($banque->getCompte());
            $ecriture->setLibellecomptedebit($banque->getNom());
            $ecriture->setDebit($debit);
            $ecriture->setSolde(-$paie->getSalaireNet());
            $ecriture->setMontant($paie->getSalaireNet());
            $ecriture->setLibelle("Paiement de salaire ".$paie->getEmploye()->getNom(). " ".$paie->getEmploye()->getPrenom());

            $ecriturecharge->setType('Banque');
            $ecriturecharge->setComptecredit("431");
            $ecriturecharge->setLibellecomptecredit("Charge Sociale");
            $ecriturecharge->setComptedebit($banque->getCompte());
            $ecriturecharge->setLibellecomptedebit($banque->getNom());
            $ecriturecharge->setDebit($debitcharge);
            $ecriturecharge->setSolde(-($paie->getTotalChargeEmploye() + $paie->getTotalChargePatronal()));
            $ecriturecharge->setMontant($paie->getTotalChargeEmploye() + $paie->getTotalChargePatronal());
            $ecriturecharge->setLibelle("Versement charge sociale");

            $ecritureimpot->setType('Banque');
            $ecritureimpot->setComptecredit("4421");
            $ecritureimpot->setLibellecomptecredit("Impot sur les revenues");
            $ecritureimpot->setComptedebit($banque->getCompte());
            $ecritureimpot->setLibellecomptedebit($banque->getNom());
            $ecritureimpot->setDebit($debitimpot);
            $ecritureimpot->setSolde(-$paie->getImpot());
            $ecritureimpot->setMontant($paie->getImpot());
            $ecritureimpot->setLibelle("Versement impot sur les revenues");

            $entityManager->persist($paie);
            $entityManager->persist($banque);
            $entityManager->persist($debit);
            $entityManager->persist($debitcharge);
            $entityManager->persist($debitimpot);
            $entityManager->persist($ecriture);
            $entityManager->persist($ecriturecharge);
            $entityManager->persist($ecritureimpot);
            $entityManager->flush();
                $this->addFlash('notice', 'Paiement effectué avec succès');

//                    return $this->redirectToRoute('depense_index', [], Response::HTTP_SEE_OTHER);
                } else {
                    $this->addFlash('notice', 'Montant non disponible');
                }




            $res['id'] = 'ok';


            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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
     * @Route("/payerTous", name="payerTous", methods={"POST"})
     */
    public function payertous(PaieRepository $paieRepository, Solde $solde, Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {

            $entityManager = $this->getDoctrine()->getManager();
            $montant = $request->get('montant');
            $banque = $entityManager->getRepository(Banque::class)->find($request->get('banque'));

            $solde = $solde->montantbanque($entityManager, $banque->getCompte());

            if ($montant <= $solde) {
                $paies = $paieRepository->findBy(['payer' => false]);



                $montantNet = 0;
                $montantcharge = 0;
                $montantimpot = 0;
                foreach ($paies as $paie) {


                    $paieSalaire = new PaieSalaire();
                    $paieSalaire->setUser($this->getUser());
                    $paieSalaire->setMontant($paie->getSalaireNet());
                    $paieSalaire->setEmploye($paie->getEmploye());
                    $paieSalaire->setCompte($banque->getCompte());
                    $entityManager->persist($paieSalaire);
                    $paie->setPayer(true);
                    $paie->setDatepaye(new \DateTime());


                    $entityManager->persist($paie);
                    $entityManager->flush();

                    $montantNet = $montantNet + $paie->getSalaireNet();
                    $montantimpot = $montantimpot + $paie->getImpot();
                    $montantcharge = $montantcharge + $paie->getTotalChargeEmploye() + $paie->getTotalChargePatronal();


                }
                $debitNet = new Debit();
                $debitCharge = new Debit();
                $debitImpot = new Debit();
                $ecritureNet = new Ecriture();
                $ecritureCharge = new Ecriture();
                $ecritureImpot = new Ecriture();

                $depenseNet = new Depense();
                $depenseNet->setUser($this->getUser());
                $depenseNet->setType("Banque");
                $depenseNet->setLibelle("Paiement salaire");
                $depenseNet->setMontant($montantNet);
                $depenseNet->setStatut("Effectuée");
                $depenseNet->setCompte("641");

                $depenseCharge = new Depense();
                $depenseCharge->setUser($this->getUser());
                $depenseCharge->setType("Banque");
                $depenseCharge->setLibelle("Versement charge sociale");
                $depenseCharge->setMontant($montantcharge);
                $depenseCharge->setStatut("Effectuée");
                $depenseCharge->setCompte("431");

                $depenseImpot = new Depense();
                $depenseImpot->setUser($this->getUser());
                $depenseImpot->setType("Banque");
                $depenseImpot->setLibelle("Versement Impot les revenues");
                $depenseImpot->setMontant($montantimpot);
                $depenseImpot->setStatut("Effectuée");
                $depenseImpot->setCompte("4421");

                $debitNet->setCompte($banque->getCompte());
                $debitNet->setType('Banque');
                $debitNet->setDepense($depenseNet);
                $debitNet->setMontant($montantNet);

                $debitCharge->setCompte($banque->getCompte());
                $debitCharge->setType('Banque');
                $debitCharge->setDepense($depenseCharge);
                $debitCharge->setMontant($montantcharge);

                $debitImpot->setCompte($banque->getCompte());
                $debitImpot->setType('Banque');
                $debitImpot->setDepense($depenseImpot);
                $debitImpot->setMontant($montantimpot);

                $ecritureNet->setType('Banque');
                $ecritureNet->setComptecredit("641");
                $ecritureNet->setLibellecomptecredit("Salaire Personnel");
                $ecritureNet->setComptedebit($banque->getCompte());
                $ecritureNet->setLibellecomptedebit($banque->getNom());
                $ecritureNet->setDebit($debitNet);
                $ecritureNet->setSolde(-$montantNet);
                $ecritureNet->setMontant($montantNet);
                $ecritureNet->setLibelle("Paiement des salaires aux employes");

                $ecritureCharge->setType('Banque');
                $ecritureCharge->setComptecredit("431");
                $ecritureCharge->setLibellecomptecredit("Charge Sociale");
                $ecritureCharge->setComptedebit($banque->getCompte());
                $ecritureCharge->setLibellecomptedebit($banque->getNom());
                $ecritureCharge->setDebit($debitCharge);
                $ecritureCharge->setSolde(-$montantcharge);
                $ecritureCharge->setMontant($montantcharge);
                $ecritureCharge->setLibelle("Versement charges sociales");

                $ecritureImpot->setType('Banque');
                $ecritureImpot->setComptecredit("431");
                $ecritureImpot->setLibellecomptecredit("Impot sur les revenues");
                $ecritureImpot->setComptedebit($banque->getCompte());
                $ecritureImpot->setLibellecomptedebit($banque->getNom());
                $ecritureImpot->setDebit($debitImpot);
                $ecritureImpot->setSolde(-$montantimpot);
                $ecritureImpot->setMontant($montantimpot);
                $ecritureImpot->setLibelle("Versement Impot sur les revenues");

                $entityManager->persist($depenseNet);
                $entityManager->persist($depenseCharge);
                $entityManager->persist($depenseImpot);
                $entityManager->persist($debitNet);
                $entityManager->persist($debitCharge);
                $entityManager->persist($debitImpot);
                $entityManager->persist($ecritureNet);
                $entityManager->persist($ecritureCharge);
                $entityManager->persist($ecritureImpot);
                $entityManager->flush();
                $this->addFlash('notice', 'Paiement effectué avec succès');
//                    return $this->redirectToRoute('depense_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('danger', 'Montant non disponible');
            }




            $res['id'] = 'ok';


            $response = new Response();
            $response->headers->set('content-type', 'application/json');
            $re = json_encode($res);
            $response->setContent($re);
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



}
