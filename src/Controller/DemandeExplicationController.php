<?php

namespace App\Controller;

use App\Entity\DemandeExplication;
use App\Entity\Employe;
use App\Entity\Notification;
use App\Entity\ReponseExplication;
use App\Form\DemandeExplicationType;
use App\Form\ReponseExplicationType;
use App\Repository\DemandeExplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/Demande")
 */
class DemandeExplicationController extends AbstractController
{
    /**
     * @Route("/", name="demande_index")
     */
    public function index(DemandeExplicationRepository $demandeExplicationRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $demandes = $demandeExplicationRepository->findAll();
            return $this->render('demande_explication/admin/index.html.twig', [
                'demandes' => $demandes,
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
     * @Route("/new", name="explication_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $demande = new DemandeExplication();
            $form = $this->createForm(DemandeExplicationType::class, $demande);
            $form->handleRequest($request);

            $employes = $this->getDoctrine()->getRepository(Employe::class)->findAll();
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $demande->setDate(new \DateTime());
                $demande->setResponsable($security->getUser());
                $selectEmployes = $request->get('demande')['employes'];
                $demande->setStatus(false);
                if (!empty($selectEmployes)) {
                    // Recherche des employés sélectionnés
                    $employe = $this->getDoctrine()->getRepository(Employe::class);
                    $employes = $employe->findBy(['id' => $selectEmployes]);

                    // Ajout des employés à la demande d'explication
                    foreach ($employes as $employe) {
                        $demande->addEmploye($employe);

                        // **Ajout de la notification**
                        $notification = new Notification();
                        $notification->setEmploye($employe);
                        $notification->setMessage("Nouvelle demande d'explication reçue le " . (new \DateTime())->format('d/m/Y'));
                        $notification->setCreatedAt(new \DateTime());
                        $notification->setIsRead(false);
                        $notification->setLien($this->generateUrl('demande_explication_index'));

                        $entityManager->persist($notification);
                    }
                }

                $entityManager->persist($demande);
                $entityManager->flush();

                return $this->redirectToRoute('demande_index');
            }
            return $this->render("demande_explication/admin/new.html.twig", [
                'form' => $form->createView(),
                'employes' => $employes,
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
     * @Route("/{id}", name="explication_show", methods={"GET"})
     */
    public function show(DemandeExplication $demandeExplications): Response
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $reponses = $demandeExplications->getReponseExplications();
            $status = [];
            foreach ($demandeExplications->getEmploye() as $employe) {
                $reponse = $reponses->filter(function ($r) use ($employe) {
                    return $r->getEmploye() === $employe;
                })->first();
                if ($reponse) {
                    $statuts[$employe->getId()] = $reponse->getStatus();
                } else {
                    $statuts[$employe->getId()] = false;
                }
            }
            return $this->render('demande_explication/admin/show.html.twig', [
                'demandeExplications' => $demandeExplications,
                'statuts' => $statuts,
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
     * @Route("/{id}/reponse/{employeId}", name="demande_explication_reponse", methods={"GET"})
     */
    public function reponse(DemandeExplication $demandeExplication, $employeId): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $employe = null;
            foreach ($demandeExplication->getEmploye() as $emp) {
                if ($emp->getId() == $employeId) {
                    $employe = $emp;
                    break;
                }
            }
            $reponse = null;
            if ($employe) {
                foreach ($demandeExplication->getReponseExplications() as $reponseExplication) {
                    if ($reponseExplication->getEmploye()->getId() == $employeId) {
                        $reponse = $reponseExplication;
                        break;
                    }
                }
            }
            return $this->render('demande_explication/admin/reponse.html.twig', [
                'demandeExplication' => $demandeExplication,
                'employe' => $employe,
                'reponse' => $reponse,
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


    /**** Methode de l'employe***** */

    /**
     * @Route("Suivi", name="demande_explication_index", methods={"GET"})
     */
    public function suivi(Security $security, DemandeExplicationRepository $demandeExplicationRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $demandes = $demandeExplicationRepository->findByEmploye($employe);

            $demandeStatus = [];
            foreach ($demandes as $demande) {
                $status = false;
                $reponses = $demande->getReponseExplications();

                foreach ($reponses as $reponse) {
                    if ($reponse->getEmploye() === $employe) {
                        $status = $reponse->getStatus();
                        break;
                    }
                }

                $demandeStatus[] = [
                    'demande' => $demande,
                    'status' => $status,
                ];
            }

            return $this->render("demande_explication/index.html.twig", [
                'demandeStatus' => $demandeStatus,
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
     * @Route("Suivi/{id}/details", name="demande_explication_detail", methods={"POST","GET"})
     */
    public function details(Security $security, DemandeExplication $demandeExplication, Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $employe = $security->getUser();
            $reponse = new ReponseExplication();
            $form = $this->createForm(ReponseExplicationType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $reponse->setReponse($form->get('reponse')->getData());
                $reponse->setObjet($demandeExplication->getObjet());
                $reponse->setDateReponse(new \DateTime());
                $reponse->setDemande($demandeExplication);
                $reponse->setEmploye($employe);
                $reponse->setStatus(true);
                $entityManager->persist($reponse);
                $entityManager->flush();
                return $this->redirectToRoute('demande_explication_detail', ['id' => $demandeExplication->getId()]);
            }
            $reponses = $demandeExplication->getReponseExplications();
            $reponseFilter = [];
            foreach ($reponses as $reponse) {
                if ($reponse->getEmploye() === $employe) {
                    $reponseFilter[] = $reponse;
                }
            }
            return $this->render('demande_explication/detail.html.twig', [
                'demandeExplications' => $demandeExplication,
                'reponses' => $reponseFilter,
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
}
