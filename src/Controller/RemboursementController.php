<?php

namespace App\Controller;

use App\Complement\Solde;
use App\Entity\Debit;
use App\Entity\Ecriture;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Repository\AvoirRepository;
use App\Repository\RemboursementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/remboursement")
 */
class RemboursementController extends AbstractController
{
    /**
     * @Route("/", name="remboursement_index", methods={"GET"})
     */
    public function index(RemboursementRepository $remboursementRepository): Response
    {
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursementRepository->findAll(),
        ]);
    }
    /**
     * @Route("/Avoir_list", name="remboursement_avoir_index", methods={"GET"})
     */
    public function avoir_list(AvoirRepository $avoirRepository, SessionInterface $session): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('remboursement/avoir_list.html.twig', [
                'avoirs' => $avoirRepository->findby(['rebourser' => false]),

            ]);
        }  else {
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
     * @Route("/financement", name="remboursement_new", methods={"GET","POST"})
     */
    public function financement(Request $request, Solde $solde): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $montant = $solde->montantcaisse($entityManager,54);
            if($remboursement->getMontant() <= $montant) {
                $remboursement->setCompte($remboursement->getBanque()->getCompte());
                $remboursement->setType('Banque');
                $debit = new Debit();
                $debit->setRemboursement($remboursement);
                $debit->setType('Banque');
                $debit->setCompte($remboursement->getBanque()->getCompte());
                $debit->setMontant($remboursement->getMontant());

                $debitecriture = new Ecriture();
                $debitecriture->setDebit($debit);
                $debitecriture->setType('Banque');
                $debitecriture->setLibelle($remboursement->getLibele());
                $debitecriture->setSolde(-$remboursement->getMontant());
                $debitecriture->setMontant($remboursement->getMontant());
                $debitecriture->setComptedebit($remboursement->getBanque()->getCompte());
                $debitecriture->setComptecredit($remboursement->getFinancement()->getCompte());


                $entityManager->persist($remboursement);
                $entityManager->persist($debit);
                $entityManager->persist($debitecriture);
                $entityManager->flush();

                $entityManager->persist($remboursement);
                $entityManager->flush();

                return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
            }else{

            }
        }

        return $this->render('remboursement/financement.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/avoir", name="remboursement_avoir", methods={"GET","POST"})
     */
    public function avoir(Request $request, Solde $solde): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $montant = $solde->montantcaisse($entityManager,54);
            if($remboursement->getMontant() <= $montant) {
                $remboursement->setCompte(52);
                $remboursement->setType('Banque');
                $debit = new Debit();
                $debit->setRemboursement($remboursement);
                $debit->setType('Banque');
                $debit->setCompte(54);
                $debit->setMontant($remboursement->getMontant());

                $debitecriture = new Ecriture();
                $debitecriture->setDebit($debit);
                $debitecriture->setType('Banque');
                $debitecriture->setLibelle($remboursement->getLibele());
                $debitecriture->setSolde(-$remboursement->getMontant());
                $debitecriture->setMontant($remboursement->getMontant());
                $debitecriture->setComptedebit('52');
                $debitecriture->setComptecredit('40');


                $entityManager->persist($remboursement);
                $entityManager->persist($debit);
                $entityManager->persist($debitecriture);
                $entityManager->flush();

                $entityManager->persist($remboursement);
                $entityManager->flush();

                return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
            }else{

            }
        }

        return $this->render('remboursement/avoir.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remboursement_show", methods={"GET"})
     */
    public function show(Remboursement $remboursement): Response
    {
        return $this->render('remboursement/show.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="remboursement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Remboursement $remboursement): Response
    {
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('remboursement/edit.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="remboursement_delete", methods={"POST"})
     */
    public function delete(Request $request, Remboursement $remboursement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remboursement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($remboursement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
    }
}
