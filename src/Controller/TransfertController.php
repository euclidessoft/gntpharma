<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Debit;
use App\Entity\Ecriture;
use App\Entity\Transfert;
use App\Form\TransfertType;
use App\Repository\TransfertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/transfert")
 */
class TransfertController extends AbstractController
{
    /**
     * @Route("/", name="transfert_index", methods={"GET"})
     */
    public function index(TransfertRepository $transfertRepository): Response
    {
        return $this->render('transfert/index.html.twig', [
            'transferts' => $transfertRepository->findAll(),
        ]);
    }

    /**
     * @Route("/caisse", name="transfert_caisse", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $transfert = new Transfert();
        $form = $this->createForm(TransfertType::class, $transfert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $transfert->getSource('Caisse');
            $transfert->getDestination('Banque');
            $debit = new Debit();
            $debit->setTransfert($transfert);
            $debit->setType('Espece');
            $debit->setMontant(-$transfert->getMontant());
            $debitecriture = new Ecriture();
            $debitecriture->setDebit($debit);
            $debitecriture->setType('Espece');


            $credit = new Credit();
            $credit->setTransfert($transfert);
            $credit->setType('Espece');
            $credit->setMontant($transfert->getMontant());
            $creditecriture = new Ecriture();
            $creditecriture->setDebit($debit);
            $creditecriture->setType('Espece');


            $entityManager->persist($transfert);
            $entityManager->persist($debit);
            $entityManager->persist($credit);
            $entityManager->persist($debitecriture);
            $entityManager->persist($creditecriture);
            $entityManager->flush();

            return $this->redirectToRoute('transfert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transfert/caisse.html.twig', [
            'transfert' => $transfert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/banque", name="transfert_banque", methods={"GET","POST"})
     */
    public function banque(Request $request): Response
    {
        $transfert = new Transfert();
        $form = $this->createForm(TransfertType::class, $transfert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $transfert->getSource('Banque');
            $transfert->getDestination('Caisse');
            $debit = new Debit();
            $debit->setTransfert($transfert);
            $debit->setType('Banque');
            $debit->setMontant(-$transfert->getMontant());
            $debitecriture = new Ecriture();
            $debitecriture->setDebit($debit);
            $debitecriture->setType('Banque');


            $credit = new Credit();
            $credit->setTransfert($transfert);
            $credit->setType('Banque');
            $credit->setMontant($transfert->getMontant());
            $creditecriture = new Ecriture();
            $creditecriture->setDebit($debit);
            $creditecriture->setType('Banque');


            $entityManager->persist($transfert);
            $entityManager->persist($debit);
            $entityManager->persist($credit);
            $entityManager->persist($debitecriture);
            $entityManager->persist($creditecriture);
            $entityManager->flush();

            return $this->redirectToRoute('transfert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transfert/banque.html.twig', [
            'transfert' => $transfert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transfert_show", methods={"GET"})
     */
    public function show(Transfert $transfert): Response
    {
        return $this->render('transfert/show.html.twig', [
            'transfert' => $transfert,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="transfert_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Transfert $transfert): Response
    {
        $form = $this->createForm(TransfertType::class, $transfert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transfert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('transfert/edit.html.twig', [
            'transfert' => $transfert,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="transfert_delete", methods={"POST"})
     */
    public function delete(Request $request, Transfert $transfert): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transfert->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($transfert);
            $entityManager->flush();
        }

        return $this->redirectToRoute('transfert_index', [], Response::HTTP_SEE_OTHER);
    }
}