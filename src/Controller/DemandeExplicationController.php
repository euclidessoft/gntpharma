<?php

namespace App\Controller;

use App\Entity\DemandeExplication;
use App\Form\DemandeExplicationType;
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
     *@Route("/new", name="demande_explication_new", methods={"GET","POST"})
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
            $entityManager->persist($demandeExplication);
            $entityManager->flush();
            return $this->redirectToRoute('demande_explication');
        }
        return $this->render("demandeExplication/admin/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    /**
     *@Route("/show/{id}", name="demande_explication_show", methods={"GET"})
     */
    public function show(DemandeExplication $demandeExplications): Response
    {
        return $this->render("demandeExplication/admin/show.html.twig", [
            'demandeExplications' => $demandeExplications,
        ]);
    }

    /**
     * @Route("/Suivi", name="demande_explication_index")
     */
    public function suivi(Security $security, DemandeExplicationRepository $demandeExplicationRepository): Response
    {

        $employe = $security->getUser();
        $demandeExplication = $demandeExplicationRepository->findBy(['employe' => $employe]);
        return $this->render("demandeExplication/index.html.twig", [
            'demandeExplication' => $demandeExplication,
        ]);
    }

    /**
     *@Route("/Suivi/Detail/{id}", name="demande_explication_detail", methods={"GET"})
     */
    public function details(DemandeExplication $demandeExplications): Response
    {
        return $this->render("demandeExplication/detail.html.twig", [
            'demandeExplications' => $demandeExplications,
        ]);
    }

    /**
     *@Route("/Suivi/Reponse/{id}", name="reponse_demande", methods={"POST","GET"})
     */
    public function reponse(Request $request, DemandeExplication $demandeExplication): Response
    {
        $form = $this->createForm(ReponseExplicationType::class, $demandeExplication);
        $form->handleRequest($request);
       
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $demandeExplication->setDatereponse(new \DateTime());
            $demandeExplication->setStatus(true);

            $entityManager->persist($demandeExplication);
            $entityManager->flush();

            return $this->redirectToRoute("demande_explication_index");
        }
        return $this->render("demandeExplication/reponse.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
