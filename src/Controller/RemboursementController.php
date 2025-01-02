<?php

namespace App\Controller;

use App\Complement\Solde;
use App\Entity\Debit;
use App\Entity\Ecriture;
use App\Entity\Interet;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Form\RemboursementbancaireType;
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
     * @Route("/Choix_financement", name="remboursement_choix", methods={"GET","POST"})
     */
    public function financementChoix(Request $request): Response
    {
        return $this->render('remboursement/choix_remboursement.html.twig');
    }

    /**
     * @Route("/financementApport", name="remboursement_espece", methods={"GET","POST"})
     */
    public function financementapport(Request $request, Solde $solde): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $remboursement->setCompte($remboursement->getFinancement()->getCompte());

            $debit = new Debit();
            $ecriture = new Ecriture();
            $debit->setRemboursement($remboursement);
            $montant = 0;

            if($remboursement->getType() == 'Espece') {
                $montant = $solde->montantcaisse($entityManager, 54);

                    $remboursement->setType('Espece');

                    $debit->setType('Espece');
                    $debit->setCompte('54');

                    $ecriture->setType('Espece');
                    $ecriture->setComptedebit('54');
                    $ecriture->setComptecredit($remboursement->getFinancement()->getCompte());



            }else{
                    $montant = $solde->montantcaisse($entityManager, $remboursement->getBanque()->getCompte());

                    $remboursement->setType('Banque');

                    $debit->setType('Banque');
                    $debit->setCompte($remboursement->getBanque()->getCompte());

                    $ecriture->setType('Banque');
                    $ecriture->setComptedebit($remboursement->getBanque()->getCompte());
                    $ecriture->setComptecredit($remboursement->getFinancement()->getCompte());

                }



            if ($remboursement->getMontant() <= $montant) {
                $debit->setMontant($remboursement->getMontant());
                $ecriture->setDebit($debit);
                $ecriture->setLibelle($remboursement->getLibele());
                $ecriture->setSolde(-$remboursement->getMontant());
                $ecriture->setMontant($remboursement->getMontant());

                $entityManager->persist($remboursement);
                $entityManager->persist($debit);
                $entityManager->persist($ecriture);
                $entityManager->flush();

                $entityManager->persist($remboursement);
                $entityManager->flush();

                return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('notice', 'Montant non disponible');
            }

        }

        return $this->render('remboursement/financement_espece.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/financementPret", name="remboursement_banque", methods={"GET","POST"})
     */
    public function financementpret(Request $request, Solde $solde): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementbancaireType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $remboursement->setCompte($remboursement->getFinancement()->getCompte());

            $debit = new Debit();
            $debitinteret = new Debit();
            $ecriture = new Ecriture();
            $ecritureinteret = new Ecriture();

            $montant = $solde->montantbanque($entityManager, $remboursement->getFinancement()->getBanque()->getCompte());
            $totalinteret =  $remboursement->getMontant() * $remboursement->getFinancement()->getTaux() / 100;

            if (($remboursement->getMontant() + $totalinteret) <= $montant) {


            $remboursement->setType('Banque');

            $debit->setType('Banque');
            $debit->setCompte($remboursement->getFinancement()->getBanque()->getCompte());
            $debit->setMontant($remboursement->getMontant());
            $debit->setRemboursement($remboursement);


            $debitinteret->setType('Banque');
            $debitinteret->setCompte($remboursement->getFinancement()->getBanque()->getCompte());
            $debitinteret->setMontant($totalinteret);
            $debitinteret->setRemboursement($remboursement);



            $ecriture->setType('Banque');
            $ecriture->setComptedebit($remboursement->getFinancement()->getBanque()->getCompte());
            $ecriture->setComptecredit($remboursement->getFinancement()->getCompte());
            $ecriture->setDebit($debit);
            $ecriture->setLibelle($remboursement->getLibele());
            $ecriture->setSolde(-$remboursement->getMontant());
            $ecriture->setMontant($remboursement->getMontant());

            $ecritureinteret->setType('Banque');
            $ecritureinteret->setComptedebit($remboursement->getFinancement()->getBanque()->getCompte());
            $ecritureinteret->setComptecredit($remboursement->getFinancement()->getCompteinteret());
            $ecritureinteret->setDebit($debitinteret);
            $ecritureinteret->setLibelle($remboursement->getLibele());
            $ecritureinteret->setSolde(-$totalinteret);
            $ecritureinteret->setMontant($totalinteret);

            $entityManager->persist($remboursement);
            $entityManager->persist($debit);
            $entityManager->persist($ecriture);
            $entityManager->persist($debitinteret);
            $entityManager->persist($ecritureinteret);
            $entityManager->flush();

            return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
        }else{
            $this->addFlash('notice', 'Montant non disponible');
        }
        }

        return $this->render('remboursement/financement_bancaire.html.twig', [
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
