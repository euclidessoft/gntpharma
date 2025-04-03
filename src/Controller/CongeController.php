<?php

namespace App\Controller;

use App\Entity\CongeAccorder;
use App\Entity\Conges;
use App\Form\ApprouverType;
use App\Form\DemandeCongeType;
use App\Repository\CongesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/Conges")
 */
class CongeController extends AbstractController
{
    /**
     * @Route("/All", name="conges_admin_index")
     */
    public function demandeAdmin(Security $security, CongesRepository $congesRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $conges = $congesRepository->findAll();
            return $this->render('conge/admin/all.html.twig', [
                'conges' => $conges,
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


    //Start Controller de demande de congés employé

    /**
     * @Route("/Demande", name="conges_employe_index")
     */
    public function demande(Security $security, CongesRepository $congesRepository): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
//            $employe = $security->getUser();
            $conges = $congesRepository->findBy(['employe' => $this->getUser()]);
            return $this->render('conge/demande.html.twig', [
                'conges' => $conges,
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
     * @Route("/Demande/Suivi", name="conges_employe_suivi")
     */
    public function traitement(Security $security, CongesRepository $congesRepository): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQuery(
                'SELECT c FROM App\Entity\Conges c WHERE c.status IS NULL OR c.confirmer IS NULL AND c.employe = :employe'
            )
                ->setParameter('employe', $this->getUser()->getId());
//            ->setParameter('confirmer', null);

            return $this->render('conge/suivi.html.twig', [
                'conges' => $query->getResult(),
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
     * @Route("/Suivi", name="conges_admin_suivi")
     */
    public function traitementAdmin(Security $security, CongesRepository $congesRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQuery(
                'SELECT c FROM App\Entity\Conges c WHERE c.status IS NULL OR c.confirmer IS NULL'
            );

            return $this->render('conge/admin/index.html.twig', [
                'conges' => $query->getResult(),
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
     * @Route("/Demande/Accepter", name="conges_employe_accepter")
     */
    public function accepter(Security $security, CongesRepository $congesRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $conges = $congesRepository->findBy(['status' => true, 'employe' => $this->getUser()]);

            return $this->render('conge/accepter.html.twig', [
                'conges' => $conges,
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
     * @Route("/Demande/Refuse", name="conges_employe_refuser")
     */
    public function refuser(Security $security, CongesRepository $congesRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $conges = $congesRepository->findBy(['status' => false, 'employe' => $this->getUser()]);
            return $this->render('conge/refuser.html.twig', [
                'conges' => $conges,
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
     * @Route("/Accepter", name="conge_admin_accepter")
     */
    public function accepterAdmin(Security $security, CongesRepository $congesRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $conges = $congesRepository->findBy(['status' => true]);

            return $this->render('conge/admin/accepte.html.twig', [
                'conges' => $conges,
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
     * @Route("/Refuser", name="conge_admin_refuser")
     */
    public function refuserAdmin(Security $security, CongesRepository $congesRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $conges = $congesRepository->findBy(['status' => false]);

            return $this->render('conge/admin/rejete.html.twig', [
                'conges' => $conges,
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
     * @Route("/New", name="conge_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {

            $conges = new Conges();
            $form = $this->createForm(DemandeCongeType::class, $conges);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $employe = $security->getUser();
                $conges->setEmploye($employe);

                $entityManager->persist($conges);
                $entityManager->flush();

                return $this->redirectToRoute('conges_employe_suivi');
            }
            return $this->render('conge/new.html.twig', [
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

    //End Controller de demande de congés employé


    /**
     * @Route("/{id}/approuve", name="conge_approuve", methods={"GET", "POST"})
     */
    public function approuver(Request $request, Conges $conges): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {

            $conges->setDateDebutAccorder($conges->getDateDebut());
            $conges->setDateFinAccorder($conges->getDateFin());
            $form = $this->createForm(ApprouverType::class, $conges);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $startDateDemande = $conges->getDateDebut();
                $endDateDemande = $conges->getDateFin();
                $startDateAccorder = $form->get('dateDebutAccorder')->getData();
                $endDateAccorder = $form->get('dateFinAccorder')->getData();

                if (($startDateDemande != $startDateAccorder) || ($endDateDemande != $endDateAccorder)) {
                    // avec modification sur la demande

                    $conges->setDateModifier(true);
                    $conges->setStatus(true);
                    $this->addFlash('success', 'Congé accordé avec succès.');
                    $url = $this->redirectToRoute('conges_admin_suivi');

                } else {//  approuvee sans modification sur la demande

                    $conges->setStatus(true);
                    $this->addFlash('success', 'Congé accordé avec modification.');
                    $url = $this->redirectToRoute('conge_admin_accepter');
                    $conges->setConfirmer(true);
                }
                $conges->setUpdatedAt(new \DateTimeImmutable());

                $entityManager->persist($conges);
                $entityManager->flush();

                return $url;
            }

            return $this->render('conge/admin/_approuver.html.twig', [
                'form' => $form->createView(),
                'conges' => $conges,
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
     * @Route("/{id}/rejeter", name="conge_rejeter", methods={"GET", "POST"})
     */
    public function rejeter(Request $request, Conges $conges): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($this->isCsrfTokenValid('rejeter' . $conges->getId(), $request->request->get('_token'))) {
                $conges->setStatus(false);
                $conges->setConfirmer(true);
            }

            $entityManager->persist($conges);
            $entityManager->flush();

            return $this->redirectToRoute('conge_admin_refuser');
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
     * @Route("/{id}/confirmer", name="conge_confirmer", methods={"GET", "POST"})
     */
    public function confirmer(Request $request, Conges $conges)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();

//            if ($this->isCsrfTokenValid('confirmer' . $conges->getId(), $request->request->get('_token'))) {
                $conges->setConfirmer(true);
//            }

            $entityManager->persist($conges);
            $entityManager->flush();
            $this->addFlash('notice', 'Conge accepté et confirmé');

            return $this->redirectToRoute('conges_employe_accepter');
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
     * @Route("/{id}/decliner", name="conge_decliner", methods={"GET", "POST"})
     */
    public function decliner(Request $request, Conges $conges)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();

//            if ($this->isCsrfTokenValid('decliner' . $conges->getId(), $request->request->get('_token'))) {
                $conges->setConfirmer(false);
//            }

            $entityManager->persist($conges);
            $entityManager->flush();
            return $this->redirectToRoute('conges_employe_accepter');
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
