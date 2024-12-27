<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Debit;
use App\Entity\Depense;
use App\Entity\Ecriture;
use App\Form\DepenseType;
use App\Repository\DepenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/depense")
 */
class DepenseController extends AbstractController
{
    /**
     * @Route("/", name="depense_index", methods={"GET"})
     */
    public function index(DepenseRepository $depenseRepository): Response
    {
        return $this->render('depense/index.html.twig', [
            'depenses' => $depenseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="depense_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $depense = new Depense();
        $debit = new Debit();
        $ecriture = new Ecriture();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $solde = 0;
            if($depense->getType() == "Espece"){
                $solde = $this->montantcaisse();
            }
            else{
                $solde = $this->montantbanque();
            }
            if($depense->getMontant() <= $solde){
            $debit->setDepense($depense);
            $debit->setMontant($depense->getMontant());
            $ecriture->setDebit($debit);
            $ecriture->setSolde(-$depense->getMontant());
            $depense->setCompte($depense->getCategorie()->getCompte()->getNumero());
            $entityManager->persist($depense);
            $entityManager->persist($debit);
            $entityManager->persist($ecriture);
            $entityManager->flush();

            return $this->redirectToRoute('depense_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('notice', 'Montant non disponible');
            }
        }

        return $this->render('depense/new.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_show", methods={"GET"})
     */
    public function show(Depense $depense): Response
    {
        return $this->render('depense/show.html.twig', [
            'depense' => $depense,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="depense_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Depense $depense): Response
    {
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('depense_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('depense/edit.html.twig', [
            'depense' => $depense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="depense_delete", methods={"POST"})
     */
    public function delete(Request $request, Depense $depense): Response
    {
        if ($this->isCsrfTokenValid('delete'.$depense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($depense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('depense_index', [], Response::HTTP_SEE_OTHER);
    }

    private function montantcaisse(){
        $em = $this->getDoctrine()->getManager();


        $credits = $em->getRepository(Credit::class)->findAll();
        $caisse = 0;
        $debitcaisse = 0;
        foreach($credits as $credit)
        {
            if ($credit->getVersement() != null) {

                if($credit->getVersement()->getType() == 'Espece')
                {
                    $caisse = $caisse + $credit->getVersement()->getMontant();
                }
            }
            else if($credit->getFinancement() != null)
            {
                if($credit->getFinancement()->getType() == 'Espece')
                {
                    $caisse = $caisse + $credit->getFinancement()->getMontant();
                    //$gain[] = $credit;
                }

            }
            else
            {
                if($credit->getPaiement()->getType() == 'Espece')
                {
                    $caisse = $caisse + $credit->getPaiement()->getMontant();
                }

            }
        }
        $deb = $em->getRepository(Debit::class)->findAll();
        foreach($deb as $debit)
        {

//            if($debit->getDepense()->getTransfert())
//            {
//                $caisse = $caisse - $debit->getDepense()->getMontant();
//            }
//            else
//            {
                if($debit->getDepense()->getType() == 'Espece')
                {
                    $debitcaisse = $debitcaisse + $debit->getMontant();
                }
           // }
        }

        $result = $caisse - $debitcaisse;
        return $result;

    }

    private function montantbanque(){
        $em = $this->getDoctrine()->getManager();


        $credits = $em->getRepository(Credit::class)->findAll();
        $caisse = 0;
        $debitcaisse = 0;
        foreach($credits as $credit)
        {
            if ($credit->getVersement() != null) {

                if($credit->getVersement()->getType() != 'Espece')
                {
                    $caisse = $caisse + $credit->getVersement()->getMontant();
                }
            }
            else if($credit->getFinancement() != null)
            {
                if($credit->getFinancement()->getType() != 'Espece')
                {
                    $caisse = $caisse + $credit->getFinancement()->getMontant();
                    //$gain[] = $credit;
                }

            }
            else
            {
                if($credit->getPaiement()->getType() != 'Espece')
                {
                    $caisse = $caisse + $credit->getPaiement()->getMontant();
                }

            }
        }
        $deb = $em->getRepository(Debit::class)->findAll();
        foreach($deb as $debit)
        {

//            if($debit->getDepense()->getTransfert())
//            {
//                $caisse = $caisse + $debit->getDepense()->getMontant();
//            }
//            else
//            {
                if($debit->getDepense()->getType() != 'Espece')
                {
                    $debitcaisse = $debitcaisse + $debit->getMontant();
                }
          //  }
        }

        $result = $caisse - $debitcaisse;
        return $result;

    }
}
