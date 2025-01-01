<?php

namespace App\Controller;

use App\Complement\Solde as Solde;
use App\Entity\Achat;
use App\Entity\Debit;
use App\Entity\Ecriture;
use App\Form\AchatType;
use App\Repository\AchatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/achat")
 */
class AchatController extends AbstractController
{
    /**
     * @Route("/", name="achat_index", methods={"GET"})
     */
    public function index(AchatRepository $achatRepository): Response
    {
        return $this->render('achat/index.html.twig', [
            'achats' => $achatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="achat_new", methods={"GET","POST"})
     */
    public function new(Request $request, Solde $solde): Response
    {
        $achat = new Achat();
        $debit = new Debit();
        $ecriture = new Ecriture();
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $achat->setUser($this->getUser());
            $montant = 0;
            if($achat->getType() == 'Espece'){
                $montant = $solde->montantcaisse($entityManager, 54);
                $achat->setCompte($achat->getFournisseur()->getCompte());

                $debit->setType('Espece');
                $debit->setCompte(54);

                $ecriture->setType('Espece');
                $ecriture->setComptecredit($achat->getFournisseur()->getCompte());
                $ecriture->setComptedebit(54);
            }
            else{
                $montant = $solde->montantbanque($entityManager, $achat->getBanque()->getCompte());//sole compte
                $achat->setType('Banque');
                $achat->setCompte($achat->getFournisseur()->getCompte());

                $debit->setType('Banque');
                $debit->setMontant($achat->getMontant());
                $debit->setCompte($achat->getBanque()->getCompte());

                $ecriture->setType('Banque');
                $ecriture->setComptecredit($achat->getFournisseur()->getCompte());
                $ecriture->setComptedebit($achat->getBanque()->getCompte());
            }
            if($achat->getMontant() <= $montant) {
                $debit->setAchat($achat);
                $debit->setMontant($achat->getMontant());

                $ecriture->setDebit($debit);
                $ecriture->setSolde(-$achat->getMontant());
                $ecriture->setMontant($achat->getMontant());
                $ecriture->setLibelle($achat->getLibele());
                $entityManager->persist($achat);
                $entityManager->persist($debit);
                $entityManager->persist($ecriture);
                $entityManager->flush();
            return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('notice', 'Montant non disponible');
            }
        }

        return $this->render('achat/new.html.twig', [
            'achat' => $achat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="achat_show", methods={"GET"})
     */
    public function show(Achat $achat): Response
    {
        return $this->render('achat/show.html.twig', [
            'achat' => $achat,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="achat_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Achat $achat): Response
    {
        $form = $this->createForm(AchatType::class, $achat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('achat/edit.html.twig', [
            'achat' => $achat,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="achat_delete", methods={"POST"})
     */
    public function delete(Request $request, Achat $achat): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achat->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($achat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
