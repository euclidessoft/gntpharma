<?php

namespace App\Controller;

use App\Complement\Solde;
use App\Entity\Banque;
use App\Entity\Debit;
use App\Entity\Ecriture;
use App\Entity\Paie;
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
     * @Route("/payer", name="payer", methods={"POST"})
     */
    public function payer(PaieRepository $paieRepository, Solde $solde): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $debit = new Debit();
            $ecriture = new Ecriture();
                $entityManager = $this->getDoctrine()->getManager();
                $depense->setUser($this->getUser());
                $montant = 0;
                if ($depense->getType() == 'Espece') {
                    $montant = $solde->montantcaisse($entityManager, 54);
                    $depense->setCompte($depense->getCategorie()->getCompte());

                    $debit->setType('Espece');
                    $debit->setCompte(54);

                    $ecriture->setType('Espece');
                    $ecriture->setComptecredit($depense->getCategorie()->getCompte());
                    $ecriture->setComptedebit(54);
                } else {
                    $montant = $solde->montantbanque($entityManager, $depense->getBanque()->getCompte());
                    $depense->setType('Banque');
                    $depense->setCompte($depense->getCategorie()->getCompte());

                    $debit->setType('Banque');
                    $debit->setCompte($depense->getBanque()->getCompte());

                    $ecriture->setType('Banque');
                    $ecriture->setComptecredit($depense->getCategorie()->getCompte());
                    $ecriture->setComptedebit($depense->getBanque()->getCompte());
                }
                if ($depense->getMontant() <= $montant) {
                    $debit->setDepense($depense);
                    $debit->setMontant($depense->getMontant());

                    $ecriture->setDebit($debit);
                    $ecriture->setSolde(-$depense->getMontant());
                    $ecriture->setMontant($depense->getMontant());
                    $ecriture->setLibelle($depense->getLibelle());

                    $entityManager->persist($depense);
                    $entityManager->persist($debit);
                    $entityManager->persist($ecriture);
                    $entityManager->flush();

                    return $this->redirectToRoute('depense_index', [], Response::HTTP_SEE_OTHER);
                } else {
                    $this->addFlash('notice', 'Montant non disponible');
                }



           $this->addFlash('notice', 'Paiement effectué avec succès');

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
