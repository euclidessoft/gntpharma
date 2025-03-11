<?php

namespace App\Controller;

use App\Entity\Credit;
use App\Entity\Ecriture;
use App\Entity\Financement;
use App\Form\FinancementType;
use App\Form\FinancementBanqueType;
use App\Repository\FinancementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/financement")
 */
class FinancementController extends AbstractController
{
    /**
     * @Route("/", name="financement_index", methods={"GET"})
     */
    public function index(FinancementRepository $financementRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('financement/index.html.twig', [
                'financements' => $financementRepository->findAll(),
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
     * @Route("/Choix_Financement", name="financement_choix", methods={"GET"})
     */
    public function choix(): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('financement/choix_financement.html.twig');
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
     * @Route("/new", name="financement_apport", methods={"GET","POST"})
     */
    public function apport(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $financement = new Financement();

            $form = $this->createForm(FinancementType::class, $financement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                $credit = new Credit();
                $ecriture = new Ecriture();
                $credit->setFinancement($financement);// ecriture comptable
                $credit->setMontant($financement->getMontant());

                $financement->setCompte('1651' . str_pad($financement->getCompte(), 2, '0', STR_PAD_LEFT));
                if ($financement->getType() == 'Espece') {
                    $credit->setCompte(54);
                    $credit->setType('Espece');


                    $ecriture->setCredit($credit);
                    $ecriture->setType('Espece');
                    $ecriture->setComptecredit(54);
                    $ecriture->setComptedebit($financement->getCompte());

                } else {
                    $financement->setType('Banque');
                    $credit->setCompte($financement->getBanque()->getCompte());
                    $credit->setType('Banque');


                    $ecriture->setCredit($credit);
                    $ecriture->setType('Banque');
                    $ecriture->setComptecredit($financement->getBanque()->getCompte());
                    $ecriture->setComptedebit($financement->getCompte());
                }


                $ecriture->setMontant($financement->getMontant());
                $ecriture->setLibelle($financement->getMotif());
                $ecriture->setSolde($financement->getMontant());

                $entityManager->persist($financement);
                $entityManager->persist($credit);
                $entityManager->persist($ecriture);
                $entityManager->flush();

                return $this->redirectToRoute('financement_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('financement/new.html.twig', [
                'financement' => $financement,
                'form' => $form->createView(),
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
     * @Route("/Pret", name="financement_pret", methods={"GET","POST"})
     */
    public function pret(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $financement = new Financement();

            $form = $this->createForm(FinancementBanqueType::class, $financement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                $credit = new Credit();
                $credit->setFinancement($financement);// ecriture comptable
                $credit->setMontant($financement->getMontant());
                $num = $financement->getCompte();
                $financement->setCompte('162' . str_pad($num, 2, '0', STR_PAD_LEFT));
                $financement->setCompteinteret('674' . str_pad($num, 2, '0', STR_PAD_LEFT));
                $financement->setApport(false);
                $financement->setType('Banque');

                $credit->setCompte($financement->getBanque()->getCompte());
                $credit->setType('Banque');


                $ecriture = new Ecriture();
                $ecriture->setCredit($credit);
                $ecriture->setType('Banque');
                $ecriture->setComptecredit($financement->getBanque()->getCompte());
                $ecriture->setComptedebit($financement->getCompte());


                $ecriture->setMontant($financement->getMontant());
                $ecriture->setLibelle($financement->getMotif());
                $ecriture->setSolde($financement->getMontant());

                $entityManager->persist($financement);
                $entityManager->persist($credit);
                $entityManager->persist($ecriture);
                $entityManager->flush();

                return $this->redirectToRoute('financement_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('financement/financement_bancaire.html.twig', [
                'financement' => $financement,
                'form' => $form->createView(),
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
     * @Route("/{id}", name="financement_show", methods={"GET"})
     */
    public function show(Financement $financement): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            return $this->render('financement/show.html.twig', [
                'financement' => $financement,
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
     * @Route("/{id}/edit", name="financement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Financement $financement): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            $form = $this->createForm(FinancementType::class, $financement);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('financement_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('financement/edit.html.twig', [
                'financement' => $financement,
                'form' => $form->createView(),
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
     * @Route("/{id}", name="financement_delete", methods={"POST"})
     */
    public function delete(Request $request, Financement $financement): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_FINANCE')) {
            if ($this->isCsrfTokenValid('delete' . $financement->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($financement);
                $entityManager->flush();
            }

            return $this->redirectToRoute('financement_index', [], Response::HTTP_SEE_OTHER);
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
