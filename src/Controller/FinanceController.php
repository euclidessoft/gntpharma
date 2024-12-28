<?php

namespace App\Controller;

use App\Repository\EcritureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
           // }
        }

        return $this->render('finance/index.html.twig',[
            'caisse' => $caisse - $debitcaisse,
            'banque' => $banque - $debitbanque,
            'ecritures' => $ecritures,
        ]);
    }
//
//    /**
//     * @Route("/Tranfert", name="Mecque_finance_transfert")
//     */
//    public function tranfert(Request $request, ObjectManager $manager)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $spend = new Depense();
//                $form = $this->createForm(TransfertType::class, $spend);
//                if ($request->isMethod('POST'))
//                {
//                    $form->handleRequest($request);
//                    if($form->isValid())
//                    {
//                        $em = $this->getDoctrine()->getManager();
//                        // verification disponibilite
//                        /*  $credits = $em->getRepository(Credit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
//                         $totalcredit = 0;
//                         $totaldebit = 0;
//                         foreach($credits as $credit)
//                         {
//                             $totalcredit = $totalcredit + $credit->getMontant();
//                         }
//                         $debits = $em->getRepository(Debit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
//                         foreach($debits as $debit)
//                         {
//                             $totaldebit = $totaldebit + $debit->getMontant();
//                         }
//                         $fonddispo = $totalcredit - $totaldebit;*/
//                        $fonddispo = $this->montantcaisse($session->getId());
//                        // fin verf
//                        if($spend->getMontant() <= $fonddispo){
//                            $spend->setSession($session);
//                            $spend->setType('Transfert');
//                            $spend->setTransfert(true);
//                            $spend->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                            $debit = new Debit();
//                            $debit->setDepense($spend);
//                            $debit->setSession($session);
//                            $debit->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                            $em->persist($debit);
//                            $em->persist($spend);
//                            $em->flush();
//                            return $this->redirectToRoute('Mecque_finance_transfert_list');
//                        }
//                        else {
//                            $this->addFlash('notice' , 'Fond non disponible');
//                            return $this->redirectToRoute('Mecque_Account');
//                        }
//                    }
//                }
//                $response = $this->render('Finance/transfert.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else
//        {
//            $this->addFlash('notice', 'Vous n\'avez pas le droit d\'acceder a cette partie de l\'application');
//            return $this->redirectToRoute('security_login');
//        }
//    }
//
//    /**
//     * @Route("/TranfertList", name="Mecque_finance_transfert_list")
//     */
//    public function tranfertList()
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $depenses = $em->getRepository(Depense::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'transfert' => true), array('id' => 'desc'));
//                $response = $this->render('Finance/transfertlist.html.twig', array('result' => $depenses, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/TranfertEdit/{id}", name="Mecque_finance_transfert_edit")
//     */
//    public function transfertEdit(Request $request,$id)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $spend = $em->getRepository(Depense::class)->find($id);//recuperation du versememt
//                $montant = $spend->getMontant();
//                $caisse = $this->montantcaisse($session->getId());
//                $form = $this->createForm(TransfertType::class, $spend);
//                $form->remove('date');
//                if ($request->isMethod('POST'))
//                {
//                    $form->handleRequest($request);
//                    if($form->isValid())
//                    {
//                        if($montant < $spend->getMontant()){//verification si modification depasse pas les fonds dispo
//                            $dif = $spend->getMontant() - $montant;
//
//                            if( $dif <= $caisse ){// test augmentation et dispo
//
//                                $debit = $em->getrepository(Debit::class)->find($spend->getId());// recuperation du debit
//                                $debit->setMontant($spend->getMontant());
//                                $em->persist($spend);
//                                $em->persist($debit);
//                                $em->flush();
//                                $this->addFlash('notice', 'Transfert modifie');
//                            }else{
//                                $this->addFlash('notice', 'fon non disponible');
//                            }
//                        }
//                        else{
//
//                            $debit = $em->getrepository(Debit::class)->find($spend->getId());// recuperation du debit
//                            $debit->setMontant($spend->getMontant());
//                            $em->persist($spend);
//                            $em->persist($debit);
//                            $em->flush();
//                            $this->addFlash('notice', 'Transfert modifie');
//                        }
//                        return $this->redirectToRoute('Mecque_finance_transfert_list');
//
//                    }
//                }
//                $response = $this->render('Finance/transfertedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/TranfertDelete", name="Mecque_finance_transfert_delete")
//     */
//    public function delete(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            //$users = $em->getrepository(User::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
//            $depense =  $em->getrepository(Depense::class)->find($request->get('transfert'));
//            $debit =  $em->getrepository(Debit::class)->findOneBy(['depense' => $depense->getId()]);
//            $em->remove($debit);
//            $em->remove($depense);
//            $em->flush();
//            $this->addFlash('notice', 'Transfert annule');
//            $res['ok']= 'ok';
//            $response = new Response();
//            $response->headers->set('content-type','application/json');
//            $re = json_encode($res);
//            $response->setContent($re);
//            return $response;
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    public function solde(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//
//                $credits = $em->getRepository(Credit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()), array('date' => 'desc'));
//                $caisse = 0;
//                $banque = 0;
//                $debitbanque = 0;
//                $debitcaisse = 0;
//                $debits = array();
//                $gain = array();
//                $transferer = array();
//                foreach($credits as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() == 'Espece')
//                        {
//                            $caisse = $caisse + $credit->getVersement()->getMontant();
//                        }
//                        else $banque = $banque + $credit->getVersement()->getMontant();
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() == 'Espece')
//                        {
//                            $caisse = $caisse + $credit->getGain()->getMontant();
//                            //$gain[] = $credit;
//                        }
//                        else
//                        {
//                            $banque = $banque + $credit->getGain()->getMontant();
//                            //$gain[] = $credit;
//                        }
//
//                    }
//                    else
//                    {
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $caisse = $caisse + $credit->getVersementAgence()->getMontant();
//                        }
//                        else $banque = $banque + $credit->getVersementAgence()->getMontant();
//
//                    }
//                }
//                $deb = $em->getRepository(Debit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()), array('date' => 'desc'));
//                foreach($deb as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $transferer[] = $debit;//liste des fonds trnsferes
//                        $caisse = $caisse - $debit->getDepense()->getMontant();
//                        $banque = $banque + $debit->getDepense()->getMontant();
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() == 'Espece')
//                        {
//                            $debitcaisse = $debitcaisse + $debit->getMontant();
//                        }
//                        else $debitbanque = $debitbanque + $debit->getMontant();
//                        $debits[] = $debit; // liste des depenses
//                    }
//                }
//
//                $response = $this->render('Finance/solde.html.twig', array('credits' => $credits, 'caisse' => $caisse - $debitcaisse, 'banque' => $banque - $debitbanque, 'transferer' => $transferer,  'debits' => $debits, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    public function brouyard(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $credits = $em->getRepository(Credit::class)->brouyard($session->getId());
//                $creditouverture = $em->getRepository(Credit::class)->ouverture($session->getId());
//
//
//                $ouverturecaisse = 0;
//                $ouverturebanque = 0;
//                $ouverturedebitbanque = 0;
//                $ouverturedebitcaisse = 0;
//                $ouverturedebits = 0;
//                $ouverturetransferer = 0;
//                foreach($creditouverture as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersement()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getMontant();
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getGain()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getGain()->getMontant();
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersementAgence()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getVersementAgence()->getMontant();
//                    }
//                }
//
//                $caisse = array();
//                $gain = array();
//                $banque = array();
//                $debitbanque = array();
//                $debitcaisse = array();
//                $debits = array();
//                $transferer = array();
//
//                foreach($credits as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() == 'Espece')
//                        {
//                            $gain[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                }
//                $deb = $em->getRepository(Debit::class)->brouyard($session->getId());
//                $debitouverture = $em->getRepository(Debit::class)->ouverture($session->getId());
//                foreach($debitouverture as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $ouverturetransferer = $ouverturetransferer + $debit->getDepense()->getMontant();//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() == 'Espece')
//                        {
//                            $ouverturedebitcaisse = $ouverturedebitcaisse + $debit->getDepense()->getMontant();
//                        }
//                    }
//                }
//                foreach($deb as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $transferer[] =$debit;//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() == 'Espece')
//                        {
//                            $debitcaisse[] = $debit;
//                        }
//                        else $debitbanque[] = $debit;
//                    }
//                }
//                $soldeouverturecaisse = $ouverturecaisse - $ouverturedebitcaisse - $ouverturetransferer;
//
//                $response = $this->render('Finance/brouyard.html.twig', array('ouverture' => $soldeouverturecaisse,'caisse' => $caisse,'transferer' => $transferer,'gain' => $gain, 'debitcaisse' => $debitcaisse, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/LienDaysBrouyard", name="finance_days_brouyard_lien")
//     */
//    public function liendaysbrouyard(Request $request)
//    {
//        $date1= $request->get('date1');
//        $date2= $request->get('date2');
//        $lien = $this->generateUrl('finance_days_brouyard', ['date1' => $date1,'date2' => $date2]);
//        $res['ok']= $lien;
//        $response = new Response();
//        $response->headers->set('content-type','application/json');
//        $re = json_encode($res);
//        $response->setContent($re);
//        return $response;
//
//    }
//
//
//    /**
//     * @Route("/DaysBrouyard/{date1}/{date2}", name="finance_days_brouyard")
//     */
//    public function daysbrouyard(Request $request,$date1, $date2)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $credits = $em->getRepository(Credit::class)->plage($date1, $date2,$session->getId());
//                $creditouverture = $em->getRepository(Credit::class)->ouvertureplace($date1, $session->getId());
//
//
//                $ouverturecaisse = 0;
//                $ouverturebanque = 0;
//                $ouverturedebitbanque = 0;
//                $ouverturedebitcaisse = 0;
//                $ouverturedebits = 0;
//                $ouverturetransferer = 0;
//                foreach($creditouverture as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersement()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getMontant();
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getGain()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getGain()->getMontant();
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersementAgence()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getVersementAgence()->getMontant();
//                    }
//                }
//
//                $gain = array();
//                $caisse = array();
//                $banque = array();
//                $debitbanque = array();
//                $debitcaisse = array();
//                $debits = array();
//                $transferer = array();
//
//                foreach($credits as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() == 'Espece')
//                        {
//                            $gain[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                    else
//                    {
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                }
//                $deb = $em->getRepository(Debit::class)->plage($date1, $date2, $session->getId());
//                $debitouverture = $em->getRepository(Debit::class)->ouvertureplace($date1, $session->getId());
//                foreach($debitouverture as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $ouverturetransferer = $ouverturetransferer + $debit->getDepense()->getMontant();//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() == 'Espece')
//                        {
//                            $ouverturedebitcaisse = $ouverturedebitcaisse + $debit->getDepense()->getMontant();
//                        }
//                    }
//                }
//                foreach($deb as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $transferer[] =$debit;//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() == 'Espece')
//                        {
//                            $debitcaisse[] = $debit;
//                        }
//                        else $debitbanque[] = $debit;
//                    }
//                }
//                $soldeouverturecaisse = $ouverturecaisse - $ouverturedebitcaisse - $ouverturetransferer;
//
//                $response = $this->render('Finance/daysbrouyard.html.twig', array('ouverture' => $soldeouverturecaisse,'date1' => $date1,'date2' => $date2,'caisse' => $caisse,'gain' => $gain,'transferer' => $transferer, 'debitcaisse' => $debitcaisse, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/LienDayBrouyard", name="finance_day_brouyard_lien")
//     */
//    public function liendaybrouyard(Request $request)
//    {
//        $date1= $request->get('date1');
//        $lien = $this->generateUrl('finance_day_brouyard', ['jour' => $date1]);
//        $res['ok']= $lien;
//        $response = new Response();
//        $response->headers->set('content-type','application/json');
//        $re = json_encode($res);
//        $response->setContent($re);
//        return $response;
//
//    }
//
//    /**
//     * @Route("/DayBrouyard/{jour}", name="finance_day_brouyard")
//     */
//    public function daybrouyard(Request $request, $jour)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $credits = $em->getRepository(Credit::class)->daybrouyard($jour, $session->getId());
//                $creditouverture = $em->getRepository(Credit::class)->ouvertureplace($jour, $session->getId());
//
//
//                $ouverturecaisse = 0;
//                $ouverturebanque = 0;
//                $ouverturedebitbanque = 0;
//                $ouverturedebitcaisse = 0;
//                $ouverturedebits = 0;
//                $ouverturetransferer = 0;
//                foreach($creditouverture as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersement()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getMontant();
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getGain()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getGain()->getMontant();
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersementAgence()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getVersementAgence()->getMontant();
//                    }
//                }
//
//                $caisse = array();
//                $gain = array();
//                $banque = array();
//                $debitbanque = array();
//                $debitcaisse = array();
//                $debits = array();
//                $transferer = array();
//
//                foreach($credits as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() == 'Espece')
//                        {
//                            $gain[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                }
//                $deb = $em->getRepository(Debit::class)->daybrouyard($jour,$session->getId());
//                $debitouverture = $em->getRepository(Debit::class)->ouvertureplace($jour, $session->getId());
//                foreach($debitouverture as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $ouverturetransferer = $ouverturetransferer + $debit->getDepense()->getMontant();//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() == 'Espece')
//                        {
//                            $ouverturedebitcaisse = $ouverturedebitcaisse + $debit->getDepense()->getMontant();
//                        }
//                    }
//                }
//                foreach($deb as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $transferer[] =$debit;//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() == 'Espece')
//                        {
//                            $debitcaisse[] = $debit;
//                        }
//                        else $debitbanque[] = $debit;
//                    }
//                }
//                $soldeouverturecaisse = $ouverturecaisse - $ouverturedebitcaisse - $ouverturetransferer;
//
//                $response = $this->render('Finance/daybrouyard.html.twig', array('ouverture' => $soldeouverturecaisse,'day' => $jour,'caisse' => $caisse,'gain' => $gain,'transferer' => $transferer, 'debitcaisse' => $debitcaisse, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/BrouyardBanque", name="finance_brouyardbanque")
//     */
//    public function brouyardbanque(Request $request)
//    {// caise devient banque pqr soucis de temps
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $credits = $em->getRepository(Credit::class)->brouyard($session->getId());
//                $creditouverture = $em->getRepository(Credit::class)->ouverture($session->getId());
//
//
//                $ouverturecaisse = 0;
//                $ouverturebanque = 0;
//                $ouverturedebitbanque = 0;
//                $ouverturedebitcaisse = 0;
//                $ouverturedebits = 0;
//                $ouverturetransferer = 0;
//                foreach($creditouverture as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() != 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersement()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getMontant();
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() != 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getGain()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getGain()->getMontant();
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersementAgence()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getVersementAgence()->getMontant();
//                    }
//                }
//
//                $caisse = array();
//                $gain = array();
//                $banque = array();
//                $debitbanque = array();
//                $debitcaisse = array();
//                $debits = array();
//                $transferer = array();
//
//                foreach($credits as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() != 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() != 'Espece')
//                        {
//                            $gain[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                    else
//                    {
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                }
//                $deb = $em->getRepository(Debit::class)->brouyard($session->getId());
//                $debitouverture = $em->getRepository(Debit::class)->ouverture($session->getId());
//                foreach($debitouverture as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $ouverturetransferer = $ouverturetransferer + $debit->getDepense()->getMontant();//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() != 'Espece')
//                        {
//                            $ouverturedebitcaisse = $ouverturedebitcaisse + $debit->getDepense()->getMontant();
//                        }
//                    }
//                }
//                foreach($deb as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $transferer[] =$debit;//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() != 'Espece')
//                        {
//                            $debitcaisse[] = $debit;
//                        }
//                        else $debitbanque[] = $debit;
//                    }
//                }
//                $soldeouverturecaisse = $ouverturecaisse - $ouverturedebitcaisse + $ouverturetransferer;
//
//                $response = $this->render('Finance/brouyardbanque.html.twig', array('ouverture' => $soldeouverturecaisse,'caisse' => $caisse,'transferer' => $transferer,'gain' => $gain, 'debitcaisse' => $debitcaisse, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/LienDaysBrouyardBanque", name="finance_days_brouyardbanque_lien")
//     */
//    public function liendaysbrouyardbanque(Request $request)
//    {
//        $date1= $request->get('date1');
//        $date2= $request->get('date2');
//        $lien = $this->generateUrl('finance_days_brouyardbanque', ['date1' => $date1,'date2' => $date2]);
//        $res['ok']= $lien;
//        $response = new Response();
//        $response->headers->set('content-type','application/json');
//        $re = json_encode($res);
//        $response->setContent($re);
//        return $response;
//
//    }
//
//    /**
//     * @Route("/DaysBrouyardBanque/{date1}/{date2}", name="finance_days_brouyardbanque")
//     */
//    public function daysbrouyardbanque(Request $request, $date1, $date2)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $credits = $em->getRepository(Credit::class)->plage($date1, $date2,$session->getId());
//                $creditouverture = $em->getRepository(Credit::class)->ouvertureplace($date1, $session->getId());
//
//
//                $ouverturecaisse = 0;
//                $ouverturebanque = 0;
//                $ouverturedebitbanque = 0;
//                $ouverturedebitcaisse = 0;
//                $ouverturedebits = 0;
//                $ouverturetransferer = 0;
//                foreach($creditouverture as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() != 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersement()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getMontant();
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() != 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getGain()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getGain()->getMontant();
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersementAgence()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getVersementAgence()->getMontant();
//                    }
//                }
//
//                $caisse = array();
//                $gain = array();
//                $banque = array();
//                $debitbanque = array();
//                $debitcaisse = array();
//                $debits = array();
//                $transferer = array();
//
//                foreach($credits as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() != 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() != 'Espece')
//                        {
//                            $gain[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                }
//                $deb = $em->getRepository(Debit::class)->plage($date1, $date2,$session->getId());
//                $debitouverture = $em->getRepository(Debit::class)->ouvertureplace($date1, $session->getId());
//                foreach($debitouverture as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $ouverturetransferer = $ouverturetransferer + $debit->getDepense()->getMontant();//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() != 'Espece')
//                        {
//                            $ouverturedebitcaisse = $ouverturedebitcaisse + $debit->getDepense()->getMontant();
//                        }
//                    }
//                }
//                foreach($deb as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $transferer[] =$debit;//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() != 'Espece')
//                        {
//                            $debitcaisse[] = $debit;
//                        }
//                        else $debitbanque[] = $debit;
//                    }
//                }
//                $soldeouverturecaisse = $ouverturecaisse - $ouverturedebitcaisse + $ouverturetransferer;
//
//                $response = $this->render('Finance/daysbrouyardbanque.html.twig', array('ouverture' => $soldeouverturecaisse,'date1' => $date1,'date2' => $date2,'caisse' => $caisse,'gain' => $gain,'transferer' => $transferer, 'debitcaisse' => $debitcaisse, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/LienDayBrouyardbanque", name="finance_day_brouyardbanque_lien")
//     */
//    public function liendaybrouyardbanque(Request $request)
//    {
//        $date1= $request->get('date1');
//        $lien = $this->generateUrl('finance_day_brouyardbanque', ['jour' => $date1]);
//        $res['ok']= $lien;
//        $response = new Response();
//        $response->headers->set('content-type','application/json');
//        $re = json_encode($res);
//        $response->setContent($re);
//        return $response;
//
//    }
//
//    /**
//     * @Route("/DayBrouyardBanque/{jour}", name="finance_day_brouyardbanque")
//     */
//    public function daybrouyardbanque(Request $request, $jour)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $credits = $em->getRepository(Credit::class)->daybrouyard($jour, $session->getId());
//                $creditouverture = $em->getRepository(Credit::class)->ouvertureplace($jour, $session->getId());
//
//
//                $ouverturecaisse = 0;
//                $ouverturebanque = 0;
//                $ouverturedebitbanque = 0;
//                $ouverturedebitcaisse = 0;
//                $ouverturedebits = 0;
//                $ouverturetransferer = 0;
//                foreach($creditouverture as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() != 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersement()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque + $credit->getMontant();
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() != 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getGain()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getGain()->getMontant();
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $ouverturecaisse = $ouverturecaisse + $credit->getVersementAgence()->getMontant();
//                        }
//                        else $ouverturebanque = $ouverturebanque +$credit->getVersementAgence()->getMontant();
//                    }
//                }
//
//                $caisse = array();
//                $gain = array();
//                $banque = array();
//                $debitbanque = array();
//                $debitcaisse = array();
//                $debits = array();
//                $transferer = array();
//
//                foreach($credits as $credit)
//                {
//                    if ($credit->getVersement() != null) {
//
//                        if($credit->getVersement()->getType() != 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                    else if($credit->getGain() != null)
//                    {
//                        if($credit->getGain()->getType() != 'Espece')
//                        {
//                            $gain[] = $credit;
//                        }
//                        else $banque[] = $credit;
//
//                    }
//                    else
//                    {//partie gestion versementagence
//                        if($credit->getVersementAgence()->getType() == 'Espece')
//                        {
//                            $caisse[] = $credit;
//                        }
//                        else $banque[] = $credit;
//                    }
//                }
//                $deb = $em->getRepository(Debit::class)->daybrouyard($jour,$session->getId());
//                $debitouverture = $em->getRepository(Debit::class)->ouvertureplace($jour, $session->getId());
//                foreach($debitouverture as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $ouverturetransferer = $ouverturetransferer + $debit->getDepense()->getMontant();//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() != 'Espece')
//                        {
//                            $ouverturedebitcaisse = $ouverturedebitcaisse + $debit->getDepense()->getMontant();
//                        }
//                    }
//                }
//                foreach($deb as $debit)
//                {
//
//                    if($debit->getDepense()->getTransfert())
//                    {
//                        $transferer[] =$debit;//liste des fonds trnsferes
//                    }
//                    else
//                    {
//                        if($debit->getDepense()->getType() != 'Espece')
//                        {
//                            $debitcaisse[] = $debit;
//                        }
//                        else $debitbanque[] = $debit;
//                    }
//                }
//                $soldeouverturecaisse = $ouverturecaisse - $ouverturedebitcaisse + $ouverturetransferer;
//
//                $response = $this->render('Finance/daybrouyardbanque.html.twig', array('ouverture' => $soldeouverturecaisse,'day' => $jour,'caisse' => $caisse,'gain' => $gain,'transferer' => $transferer, 'debitcaisse' => $debitcaisse, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/VersementList", name="Finance_versement_list")
//     */
//    public function versementList(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//
//                $credits = $em->getRepository(Credit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'gain' => null), array('date' => 'asc'));
//
//
//                $response = $this->render('Finance/versementlist.html.twig', array('credits' => $credits, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//
//    /**
//     * @Route("/ComingMoneyEdit/{id}", name="gain_edit")
//     */
//    public function gainEdit(Request $request,$id)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $gain = $em->getRepository(Gain::class)->find($id);//recuperation du versememt
//                $form = $this->createForm(GainType::class, $gain);
//                $form->remove('date');
//                if ($request->isMethod('POST'))
//                {
//                    $form->handleRequest($request);
//                    if($form->isValid())
//                    {
//                        $credit = $em->getrepository(Credit::class)->findOneBy(['gain' =>$gain->getId()]);// recuperation du credit
//                        $credit->setMontant($gain->getMontant());
//                        $em->persist($gain);
//                        $em->persist($credit);
//                        $em->flush();
//                        $this->addFlash('notice', 'Financement modifié');
//                        return $this->redirectToRoute('Mecque_GainList');
//                    }
//                }
//                $response = $this->render('Finance/gainedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    /**
//     * @Route("/GainDelete", name="gain_delete")
//     */
//    public function gaindelete(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            //$users = $em->getrepository(User::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
//            $gain =  $em->getrepository(Gain::class)->find($request->get('gain'));
//            $credit =  $em->getrepository(Credit::class)->findOneBy(['gain' => $gain->getId()]);
//            $em->remove($credit);
//            $em->remove($gain);
//            $em->flush();
//            $this->addFlash('notice', 'Financement supprimé');
//            $res['id']= 'ok';
//            $response = new Response();
//            $response->headers->set('content-type','application/json');
//            $re = json_encode($res);
//            $response->setContent($re);
//            return $response;
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    private function montantcaisse($session){
//        $em = $this->getDoctrine()->getManager();
//
//
//        $credits = $em->getRepository(Credit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session), array('date' => 'desc'));
//        $caisse = 0;
//        $debitcaisse = 0;
//        foreach($credits as $credit)
//        {
//            if ($credit->getVersement() != null) {
//
//                if($credit->getVersement()->getType() == 'Espece')
//                {
//                    $caisse = $caisse + $credit->getVersement()->getMontant();
//                }
//            }
//            else if($credit->getGain() != null)
//            {
//                if($credit->getGain()->getType() == 'Espece')
//                {
//                    $caisse = $caisse + $credit->getGain()->getMontant();
//                    //$gain[] = $credit;
//                }
//
//            }
//            else
//            {
//                if($credit->getVersementAgence()->getType() == 'Espece')
//                {
//                    $caisse = $caisse + $credit->getVersementAgence()->getMontant();
//                }
//
//            }
//        }
//        $deb = $em->getRepository(Debit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session), array('date' => 'desc'));
//        foreach($deb as $debit)
//        {
//
//            if($debit->getDepense()->getTransfert())
//            {
//                $caisse = $caisse - $debit->getDepense()->getMontant();
//            }
//            else
//            {
//                if($debit->getDepense()->getType() == 'Espece')
//                {
//                    $debitcaisse = $debitcaisse + $debit->getMontant();
//                }
//            }
//        }
//
//        $result = $caisse - $debitcaisse;
//        return $result;
//
//    }
//
//    private function montantbanque($session){
//        $em = $this->getDoctrine()->getManager();
//
//
//        $credits = $em->getRepository(Credit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session), array('date' => 'desc'));
//        $caisse = 0;
//        $debitcaisse = 0;
//        foreach($credits as $credit)
//        {
//            if ($credit->getVersement() != null) {
//
//                if($credit->getVersement()->getType() != 'Espece')
//                {
//                    $caisse = $caisse + $credit->getVersement()->getMontant();
//                }
//            }
//            else if($credit->getGain() != null)
//            {
//                if($credit->getGain()->getType() != 'Espece')
//                {
//                    $caisse = $caisse + $credit->getGain()->getMontant();
//                    //$gain[] = $credit;
//                }
//
//            }
//            else
//            {
//                if($credit->getVersementAgence()->getType() != 'Espece')
//                {
//                    $caisse = $caisse + $credit->getVersementAgence()->getMontant();
//                }
//
//            }
//        }
//        $deb = $em->getRepository(Debit::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session), array('date' => 'desc'));
//        foreach($deb as $debit)
//        {
//
//            if($debit->getDepense()->getTransfert())
//            {
//                $caisse = $caisse + $debit->getDepense()->getMontant();
//            }
//            else
//            {
//                if($debit->getDepense()->getType() != 'Espece')
//                {
//                    $debitcaisse = $debitcaisse + $debit->getMontant();
//                }
//            }
//        }
//
//        $result = $caisse - $debitcaisse;
//        return $result;
//
//    }
//
//    public function spend(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $spend = new Depense();
//                $form = $this->createForm(DepenseType::class, $spend);
//                if ($request->isMethod('POST'))
//                {
//                    $form->handleRequest($request);
//                    if($form->isValid())
//                    {
//                        if($spend->getType() == "Espece"){
//                            if($spend->getMontant() <= $this->montantcaisse($session->getId())){
//                                $em = $this->getDoctrine()->getManager();
//                                $spend->setSession($session);
//                                $spend->setTransfert(false);
//                                $spend->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                                $debit = new Debit();
//                                $debit->setDepense($spend);
//                                $debit->setSession($session);
//                                $debit->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                                $em->persist($debit);
//                                $em->persist($spend);
//                                $em->flush();
//                                $this->addFlash('success', 'Dépense enregistrée');
//                            }else{
//                                $this->addFlash('notice', 'Montant non disponible');
//                            }
//
//                        }else{
//
//                            if($spend->getMontant() <= $this->montantbanque($session->getId())){
//                                $em = $this->getDoctrine()->getManager();
//                                $spend->setSession($session);
//                                $spend->setTransfert(false);
//                                $spend->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                                $debit = new Debit();
//                                $debit->setDepense($spend);
//                                $debit->setSession($session);
//                                $debit->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                                $em->persist($debit);
//                                $em->persist($spend);
//                                $em->flush();
//                                $this->addFlash('success', 'Dépense enregistrée');
//                            }else{
//                                $this->addFlash('notice', 'Montant non disponible');
//                            }
//                        }
//                        return $this->redirectToRoute('Mecque_SpendList');
//                    }
//                }
//                $response = $this->render('Default/spend.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    public function spendList()
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $depenses = $em->getRepository(Depense::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'transfert' => false));
//                $response = $this->render('Default/spendlist.html.twig', array('result' => $depenses, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    public function gain(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $gain = new Gain();
//                $form = $this->createForm(GainType::class, $gain);
//                if ($request->isMethod('POST'))
//                {
//                    $form->handleRequest($request);
//                    if($form->isValid())
//                    {
//                        $em = $this->getDoctrine()->getManager();
//                        $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//                        $gain->setSession($session);
//                        $gain->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                        $credit = new Credit();
//                        $credit->setGain($gain);
//                        $credit->setSession($session);
//                        $credit->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
//                        $em->persist($credit);
//                        $em->persist($gain);
//                        $em->flush();
//                        $this->addFlash('notice', 'Financement ajouté');
//                        $response = $this->redirectToRoute('Mecque_GainList');
//                        $response->setSharedMaxAge(0);
//                        $response->headers->addCacheControlDirective('no-cache', true);
//                        $response->headers->addCacheControlDirective('no-store', true);
//                        $response->headers->addCacheControlDirective('must-revalidate', true);
//                        $response->setCache([
//                            'max_age' => 0,
//                            'private' => true,
//                        ]);
//                        return $response;
//                    }
//                }
//                return $this->render('Default/gain.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    public function gainList(Request $request)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $gains = $em->getRepository(Gain::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
//                $response = $this->render('Default/gaintlist.html.twig', array('result' => $gains, 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//
//    public function spendEdit(Request $request,$id)
//    {
//        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
//        {
//            $em = $this->getDoctrine()->getManager();
//            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
//            if(!empty($session))
//            {
//                $spend = $em->getRepository(Depense::class)->find($id);//recuperation du versememt
//                $form = $this->createForm(DepenseType::class, $spend);
//                $type = $spend->getType();
//                if($type == "Espece")$tamp =true; else $tamp = false;
//                $montant = $spend->getMontant();
//                $caisse = $this->montantcaisse($session->getId());
//                $banque = $this->montantbanque($session->getId());
//                $form->remove('date');
//                if ($request->isMethod('POST'))
//                {
//                    $form->handleRequest($request);
//                    if($form->isValid())
//                    {
//                        if($tamp == true && $spend->getType() == "Espece"){
//                            // toujours espece
//                            if($montant < $spend->getMontant()){
//                                if(($spend->getMontant()- $montant) <= $caisse ){// controle difference et caisse
//                                    $debit = $em->getrepository(Debit::class)->findOneBy(['depense' => $spend->getId()]);// recuperation du debit
//                                    $debit->setMontant($spend->getMontant());
//                                    $em->persist($spend);
//                                    $em->persist($debit);
//                                    $em->flush();
//                                }
//                                else{
//                                    $this->addFlash('notice', 'Fonds non disponibles');
//                                }
//
//                            }
//                            else{
//                                $debit = $em->getrepository(Debit::class)->findOneBy(['depense' => $spend->getId()]);// recuperation du debit
//                                $debit->setMontant($spend->getMontant());
//                                $em->persist($spend);
//                                $em->persist($debit);
//                                $em->flush();
//                            }
//                        }
//                        else if($tamp == true && $spend->getType() != "Espece"){
//                            // espece vers banque
//                            if($spend->getMontant() <= $banque ){// controle disponibilite
//                                $debit = $em->getrepository(Debit::class)->findOneBy(['depense' => $spend->getId()]);// recuperation du debit
//                                $debit->setMontant($spend->getMontant());
//                                $em->persist($spend);
//                                $em->persist($debit);
//                                $em->flush();
//                            }
//                            else{
//                                $this->addFlash('notice', 'Fonds non disponibles');
//                            }
//                        }
//                        else if($tamp != true && $spend->getType() == "Espece"){
//                            // banque vers espece
//                            if($spend->getMontant() <= $caisse ){// controle disponibilite
//                                $debit = $em->getrepository(Debit::class)->findOneBy(['depense' => $spend->getId()]);// recuperation du debit
//                                $debit->setMontant($spend->getMontant());
//                                $em->persist($spend);
//                                $em->persist($debit);
//                                $em->flush();
//                            }
//                            else{
//                                $this->addFlash('notice', 'Fonds non disponibles');
//                            }
//                        }
//                        else if($tamp != true && $spend->getType() != "Espece"){
//                            // toujours banque
//                            if($montant < $spend->getMontant()){
//                                if(($spend->getMontant()- $montant) <= $banque ){// controle difference et caisse
//                                    $debit = $em->getrepository(Debit::class)->findOneBy(['depense' => $spend->getId()]);// recuperation du debit
//                                    $debit->setMontant($spend->getMontant());
//                                    $em->persist($spend);
//                                    $em->persist($debit);
//                                    $em->flush();
//                                }
//                                else{
//                                    $this->addFlash('notice', 'Fonds non disponibles');
//                                }
//
//                            }
//                            else{
//                                $debit = $em->getrepository(Debit::class)->findOneBy(['depense' => $spend->getId()]);// recuperation du debit
//                                $debit->setMontant($spend->getMontant());
//                                $em->persist($spend);
//                                $em->persist($debit);
//                                $em->flush();
//                            }
//                        }
//                        return $this->redirectToRoute('Mecque_SpendList');
//                    }
//                }
//                $response = $this->render('Default/spendedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
//                $response->setSharedMaxAge(0);
//                $response->headers->addCacheControlDirective('no-cache', true);
//                $response->headers->addCacheControlDirective('no-store', true);
//                $response->headers->addCacheControlDirective('must-revalidate', true);
//                $response->setCache([
//                    'max_age' => 0,
//                    'private' => true,
//                ]);
//                return $response;
//            }
//            else return $this->redirectToRoute('Mecque_SessionStart');
//        }
//        else return $this->redirect($this->generateUrl('security_login'));
//    }
//


}
