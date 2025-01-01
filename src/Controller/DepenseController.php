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
use App\Complement\Solde as Solde;

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
    public function new(Request $request, Solde $solde): Response
    {
        $depense = new Depense();
        $debit = new Debit();
        $ecriture = new Ecriture();
        $form = $this->createForm(DepenseType::class, $depense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $depense->setUser($this->getUser());
            $montant = 0;
            if($depense->getType() == 'Espece'){
                $montant = $solde->montantcaisse($entityManager, 54);
                $depense->setCompte($depense->getCategorie()->getCompte());

                $debit->setType('Espece');
                $debit->setCompte(54);

                $ecriture->setType('Espece');
                $ecriture->setComptecredit($depense->getCategorie()->getCompte());
                $ecriture->setComptedebit(54);
            }
            else{
                $montant = $solde->montantbanque($entityManager, $depense->getBanque()->getCompte());
                $depense->setType('Banque');
                $depense->setCompte($depense->getCategorie()->getCompte());

                $debit->setType('Banque');
                $debit->setCompte($depense->getBanque()->getCompte());

                $ecriture->setType('Banque');
                $ecriture->setComptecredit($depense->getCategorie()->getCompte());
                $ecriture->setComptedebit($depense->getBanque()->getCompte());
            }
            if($depense->getMontant() <= $montant){
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
}
