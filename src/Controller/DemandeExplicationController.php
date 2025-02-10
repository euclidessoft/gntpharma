<?php

namespace App\Controller;

use App\Entity\DemandeExplication;
use App\Entity\Employe;
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
        $demandes = $demandeExplicationRepository->findAll();
        return $this->render('demande_explication/admin/index.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    /**
     * @Route("/new", name="explication_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $demande = new DemandeExplication();
        $form = $this->createForm(DemandeExplicationType::class, $demande);
        $form->handleRequest($request);

        $employes = $this->getDoctrine()->getRepository(Employe::class)->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $demande->setDate(new \DateTime());
            $selectEmployes = $request->get('demande')['employes'];
            $demande->setStatus(false);
            if (!empty($selectEmployes)) {

                // Recherche des employés sélectionnés
                $employe = $this->getDoctrine()->getRepository(Employe::class);
                $employes = $employe->findBy(['id' => $selectEmployes]);

                // Ajout des employés à la demande d'explication
                foreach ($employes as $employe) {
                    $demande->addEmploye($employe);
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
    }

    /**
     * @Route("/{id}", name="explication_show", methods={"GET"})
     */
    public function show(DemandeExplication $demandeExplications): Response
    {

        $reponses = $demandeExplications->getReponseExplications();
        $status = [];
        foreach($demandeExplications->getEmploye() as $employe){
            $reponse = $reponses->filter(function($r) use ($employe) {
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
    }


    /**** Methode de l'employe***** */

    /**
     * @Route("Suivi", name="demande_explication_index", methods={"GET"})
     */
    public function suivi(Security $security, DemandeExplicationRepository $demandeExplicationRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $security->getUser();
        $demandes = $demandeExplicationRepository->findByEmploye($employe);

        return $this->render("demande_explication/index.html.twig", [
            'demandes' => $demandes,
        ]);
    }

    /**
     * @Route("Suivi/{id}/details", name="demande_explication_detail", methods={"POST","GET"})
     */
    public function details(Security $security, DemandeExplication $demandeExplication, Request $request): Response
    {
        $employe = $security->getUser();
        $reponse = new ReponseExplication();
        $form = $this->createForm(ReponseExplicationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $reponse->setReponse($form->get('reponse')->getData());
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
    }
}
