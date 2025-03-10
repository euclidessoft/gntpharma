<?php

namespace App\Controller;

use App\Entity\Sanction;
use App\Form\SanctionType;
use App\Repository\SanctionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/Sanction")
 */
class SanctionController extends AbstractController
{
    /**
     * @Route("/", name="sanction_index", methods={"GET"})
     */
    public function index(SanctionRepository $sanctionRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('sanction/admin/index.html.twig', [
                'sanctions' => $sanctionRepository->findAll(),
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
     * @Route("/Suivi/", name="sanction_suivi", methods={"GET"})
     */
    public function suivi(Security $security): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $employe = $security->getUser();
            $sanction = $this->getDoctrine()->getRepository(Sanction::class)->findBy(['employe' => $employe]);
            return $this->render('sanction/suivi.html.twig', [
                'sanctions' => $sanction,
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
     * @Route("/new", name="sanction_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $sanction = new Sanction();
            $form = $this->createForm(SanctionType::class, $sanction);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $sanction->setDateCreation(new \DateTime());
                $sanction->setCreatedAt(new \DateTime());
                $employe = $sanction->getEmploye();
                $nbreJoursConges = $employe->getNombreJoursConges();
                $typeSanction = $sanction->getTypeSanction()->getNom();
                if ($typeSanction === 'mis a pied') {
                    $dateDebut = $sanction->getDateDebut();
                    $dateFin = $sanction->getDateFin();
                    $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                    $sanction->setNombreJours($nombreJours);
                } elseif ($typeSanction === 'ponction salarial') {
                    $sanction->setNombreJours('1');
                } elseif ($typeSanction === 'Retenue sur les congés') {
                    $dateDebut = $sanction->getDateDebut();
                    $dateFin = $sanction->getDateFin();
                    $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                    if ($nbreJoursConges >= $nombreJours) {
                        $nbreJourRestant = $nbreJoursConges - $nombreJours;
                        $employe->setNombreJoursConges($nbreJourRestant);
                        $sanction->setNombreJours($nombreJours);
                        $calendar = $employe->getCalendriers();
                        foreach ($calendar as $calendrier) {
                            $dateFinConges = (clone $calendrier->getDateDebut())->modify('+' . $nbreJourRestant . ' days');
                            $calendrier->setDateFin($dateFinConges);
                            $entityManager->persist($calendrier);
                        }
                    }
                }
                $entityManager->persist($sanction);
                $entityManager->persist($employe);
                $entityManager->flush();

                return $this->redirectToRoute('sanction_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('sanction/admin/new.html.twig', [
                'sanction' => $sanction,
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
     * @Route("/{id}", name="sanction_show", methods={"GET"})
     */
    public function show(Sanction $sanction): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('sanction/admin/show.html.twig', [
                'sanction' => $sanction,
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
     * @Route("/{id}/edit", name="sanction_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sanction $sanction): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $form = $this->createForm(SanctionType::class, $sanction);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('sanction_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('sanction/admin/edit.html.twig', [
                'sanction' => $sanction,
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
     * @Route("/{id}", name="sanction_delete", methods={"POST"})
     */
    public function delete(Request $request, Sanction $sanction): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            if ($this->isCsrfTokenValid('delete' . $sanction->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($sanction);
                $entityManager->flush();
            }

            return $this->redirectToRoute('sanction_index', [], Response::HTTP_SEE_OTHER);
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
