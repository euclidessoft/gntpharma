<?php

namespace App\Controller;

use App\Entity\DemandeExplication;
use App\Entity\Employe;
use App\Entity\ReponseExplication;
use App\Form\DemandeExplicationType;
use App\Form\ExplicationCollectifType;
use App\Form\ReponseExplicationType;
use App\Repository\DemandeExplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/Explication")
 */
class DemandeExplicationController extends AbstractController
{
    /**
     * @Route("/", name="demande_explication")
     */
    public function index(DemandeExplicationRepository $demandeExplicationRepository): Response
    {
        $demandeExplication = $demandeExplicationRepository->findAll();

        return $this->render('demandeExplication/admin/index.html.twig', [
            'demandeExplication' => $demandeExplication,
        ]);
    }

    /**
     * @Route("/Choix_demande", name="demande_choix", methods={"GET","POST"})
     */
    public function demandeExplication(Request $request): Response
    {
        return $this->render('demandeExplication/admin/demande.html.twig');
    }


    /**
     * @Route("/new", name="demande_explication_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $demandeExplication = new DemandeExplication();
        $form = $this->createForm(DemandeExplicationType::class, $demandeExplication);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $responsable = $security->getUser();
            $demandeExplication->setDate(new \DateTime());
            $demandeExplication->setStatus(false);
            $demandeExplication->setResponsable($responsable);
            $employes = $demandeExplication->getEmploye();

            foreach ($employes as $employe) {
                $demandeExplication->addEmploye($employe);
            }
            $entityManager->persist($demandeExplication);
            $entityManager->flush();
        }

        return $this->render("demandeExplication/admin/new.html.twig", [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Suivi", name="demande_explication_index")
     */
    public function suivi(Security $security, DemandeExplicationRepository $demandeExplicationRepository): Response
    {
        $employe = $security->getUser();
        $demandeExplication = $demandeExplicationRepository->findByEmploye($employe);
        return $this->render("demandeExplication/index.html.twig", [
            'demandeExplication' => $demandeExplication,
        ]);
    }


    /**
     * @Route("/detail/{id}", name="demande_explication_details", methods={"GET"})
     */
    public function detail(DemandeExplication $demandeExplications): Response
    {
        return $this->render("demandeExplication/admin/details.html.twig", [
            'demandeExplication' => $demandeExplications,
        ]);
    }


    /**
     * @Route("/Suivi/Detail/{id}", name="demande_explication_detail", methods={"GET","POST"})
     */
    public function reponse(Request $request, DemandeExplication $demandeExplications, Security $security): Response
    {

        $reponseExplication = new ReponseExplication();

        $form = $this->createForm(ReponseExplicationType::class, $reponseExplication);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $security->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $reponseExplication->setDateReponse(new \DateTime());
            $reponseExplication->setStatus(true);
            $reponseExplication->setDemandeExplication($demandeExplications);
            $reponseExplication->setEmploye($employe);
            $reponseExplication->setReponse($form->get('reponse')->getData());
            $demandeExplications->setStatus(true);

            $entityManager->persist($reponseExplication);
            $entityManager->flush();

            return $this->redirectToRoute('demande_explication_detail', ['id' => $demandeExplications->getId()]);
        }

        $responses = $entityManager->getRepository(ReponseExplication::class)->findBy([
            'demandeExplication' => $demandeExplications,
            'employe' => $employe,
        ]);

        return $this->render("demandeExplication/detail.html.twig", [
            'demandeExplications' => $demandeExplications,
            'form' => $form->createView(),
            'responses' => $responses,
        ]);
    }



}