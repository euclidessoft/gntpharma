<?php

namespace App\Controller;

use App\Entity\Banque;
use App\Entity\Ecriture;
use App\Repository\EcritureRepository;
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
        $ecritures = $repository->findAll();
        $caisse = 0;
        $banque = 0;
        $debitbanque = 0;
        $debitcaisse = 0;
        foreach($ecritures as $ecriture)
        {
            $credit= null;
            $debit= null;
            if($ecriture->getCredit() != null) {
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

        return $this->render('finance/index.html.twig',[
            'caisse' => $caisse - $debitcaisse,
            'banque' => $banque - $debitbanque,
            'ecritures' => $ecritures,
        ]);
    }

    /**
     * @Route("/JournalBanque/{banque}", name="journal_banque")
     */
    public function journalbanque(EcritureRepository $repository, Banque $banque): Response
    {
        $ecritures = $repository->findAll();

        $caisse = 0;
        $bank = 0;
        $debitbanque = 0;
        $debitcaisse = 0;
        $ecrit = [];
        foreach($ecritures as $ecriture)
        {
            if($banque->getCompte() == $ecriture->getComptecredit() || $banque->getCompte() == $ecriture->getComptedebit()) {
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

        return $this->render('banque/journal_banque.html.twig',[
            'caisse' => $caisse - $debitcaisse,
            'banque' => $bank - $debitbanque,
            'ecritures' => $ecrit,
        ]);
    }

    /**
     * @Route("/JournalCompte/{banque}", name="journal_compte")
     */
    public function journalCompte(EcritureRepository $repository, Banque $banque): Response
    {
        $ecritures = $repository->findAll();

        $caisse = 0;
        $bank = 0;
        $debitbanque = 0;
        $debitcaisse = 0;
        $ecrit = [];
        foreach($ecritures as $ecriture)
        {
            if($banque->getCompte() == $ecriture->getComptecredit() || $banque->getCompte() == $ecriture->getComptedebit()) {
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

        return $this->render('banque/journal_banque.html.twig',[
            'caisse' => $caisse - $debitcaisse,
            'banque' => $bank - $debitbanque,
            'ecritures' => $ecrit,
        ]);
    }

    /**
     * @Route("/Brouillard", name="brouyard")
     */
    public function brouyard(EcritureRepository $repository): Response
    {
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
        foreach($ecritures as $ecriture)
        {
            $credit= null;
            $debit= null;
            if($ecriture->getCredit() != null) {
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

        foreach($ouverture as $ecriture)
        {
            $credit= null;
            $debit= null;
            if($ecriture->getCredit() != null) {
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

        return $this->render('finance/brouyard.html.twig',[
            'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
            'banque' => $banque - $debitbanque + $banqueouv- $debitbanqueouv,
            'ecritures' => $ecritures,
            'ouverture' => ($caisseouv - $debitcaisseouv) + ($banqueouv- $debitbanqueouv)
        ]);
    }

    /**
     * @Route("/BrouillardCaisse", name="brouyard_caisse")
     */
    public function brouyardCaisse(EcritureRepository $repository): Response
    {
        $ecritures = $repository->brouyardcaisse();
        $ouverture = $repository->ouverturecaisse();
        $caisse = 0;
        $caisseouv = 0;
        $debitcaisse = 0;
        $debitcaisseouv = 0;
        foreach($ecritures as $ecriture)
        {
            $credit= null;
            $debit= null;
            if($ecriture->getCredit() != null) {
                $credit = $ecriture->getCredit();
                $caisse = $caisse + $credit->getMontant();
            } else {

                $debit = $ecriture->getDebit();
                $debitcaisse = $debitcaisse + $debit->getMontant();

            }

        }

        foreach($ouverture as $ecriture)
        {
            $credit= null;
            $debit= null;
            if($ecriture->getCredit() != null) {
                $credit = $ecriture->getCredit();
                $caisseouv = $caisseouv + $credit->getMontant();
            } else {

                $debit = $ecriture->getDebit();
                $debitcaisseouv = $debitcaisseouv + $debit->getMontant();

            }

        }

        return $this->render('finance/brouyard_caisse.html.twig',[
            'caisse' => $caisse - $debitcaisse + $caisseouv - $debitcaisseouv,
            'ecritures' => $ecritures,
            'ouverture' => $caisseouv - $debitcaisseouv,
        ]);
    }

    /**
     * @Route("/BrouillardBanque", name="brouyard_banque")
     */
    public function brouyardBanque(EcritureRepository $repository): Response
    {
        $ecritures = $repository->brouyardbanque();
        $ouverture = $repository->ouverturebanque();
        $banque = 0;
        $banqueouv = 0;
        $debitbanque = 0;
        $debitbanqueouv = 0;
        foreach($ecritures as $ecriture)
        {
            $credit= null;
            $debit= null;
            if($ecriture->getCredit() != null) {
                $credit = $ecriture->getCredit();
                    $banque = $banque + $credit->getMontant();
            } else {

                $debit = $ecriture->getDebit();
                    $debitbanque = $debitbanque + $debit->getMontant();

            }

        }

        foreach($ouverture as $ecriture)
        {
            $credit= null;
            $debit= null;
            if($ecriture->getCredit() != null) {
                $credit = $ecriture->getCredit();

                    $banqueouv = $banqueouv + $credit->getMontant();
            } else {

                $debit = $ecriture->getDebit();

                    $debitbanqueouv = $debitbanqueouv + $debit->getMontant();

            }

        }

        return $this->render('finance/brouyard_banque.html.twig',[
            'banque' => $banque - $debitbanque + $banqueouv- $debitbanqueouv,
            'ecritures' => $ecritures,
            'ouverture' => $banqueouv- $debitbanqueouv,
        ]);
    }

    /**
     * @Route("/LienDayBrouyard", name="day_brouyard_lien")
     */
    public function liendaybrouyard(Request $request)
    {
        $date1= $request->get('date1');
        $lien = $this->generateUrl('finance_day_brouyard', ['jour' => $date1]);
        $res['ok']= $lien;
        $response = new Response();
        $response->headers->set('content-type','application/json');
        $re = json_encode($res);
        $response->setContent($re);
        return $response;

    }

    /**
     * @Route("/DayBrouyard/{jour}", name="day_brouyard")
     */
    public function daybrouyard(Request $request, $jour)
    {
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
    }

    /**
     * @Route("/LienDaysBrouyard", name="days_brouyard_lien")
     */
    public function liendaysbrouyard(Request $request)
    {
        $date1= $request->get('date1');
        $date2= $request->get('date2');
        $lien = $this->generateUrl('finance_days_brouyard', ['date1' => $date1,'date2' => $date2]);
        $res['ok']= $lien;
        $response = new Response();
        $response->headers->set('content-type','application/json');
        $re = json_encode($res);
        $response->setContent($re);
        return $response;

    }


    /**
     * @Route("/DaysBrouyard/{date1}/{date2}", name="days_brouyard")
     */
    public function daysbrouyard(Request $request,$date1, $date2)
    {

        $repository = $this->getDoctrine()->getManager()->getRepository(Ecriture::class);
        $ecritures = $repository->plage($date1,$date2);
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
    }


}
