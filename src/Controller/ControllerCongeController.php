<?php

namespace App\Controller;

use App\Entity\CongeAccorder;
use App\Entity\Conges;
use App\Form\ApprouverType;
use App\Repository\CongesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Conges")
 */
class ControllerCongeController extends AbstractController
{
    /**
     * @Route("/", name="conge_index")
     */
    public function index(CongesRepository $congesRepository): Response
    {
        $conges = $congesRepository->findAll();
        return $this->render('conge/index.html.twig', [
            'conge' => $conges,
        ]);
    }



//    /**
//     * @Route("/new", name="conge_new", methods={"GET", "POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $conges = new Conges();
//
//    }




    /**
     * @Route("/{id}/approuve", name="conge_approuve", methods={"GET", "POST"})
     */
    public function approuver(Request $request,Conges $conges): Response
    {
        $congesAccorder = new CongeAccorder();
        $form = $this->createForm(ApprouverType::class, $congesAccorder , [
            'conge' => $conges,
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $congesAccorder->setConges($conges);
            $congesAccorder->setDateDebutAccorder($form->get('dateDebutAccorder')->getData());
            $congesAccorder->setDateFinAccorder($form->get('dateFinAccorder')->getData());
            $congesAccorder->setEmploye($conges->getEmploye());
            $congesAccorder->setType($conges->getType());
            $congesAccorder->setStatus(true);

            $conges->setStatus(1);
            $entityManager->persist($congesAccorder);
            $entityManager->flush();
            $this->addFlash('success', 'Le congé a été accordé avec succès.');

            return $this->redirectToRoute('conge_index');
        }

        return $this->render('conge/_approuver.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}/rejeter", name="conge_rejeter", methods={"GET", "POST"})
     */
    public function rejeter(Request $request,Conges $conges): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($this->isCsrfTokenValid('rejeter'.$conges->getId(), $request->request->get('_token'))){
            $conges->setStatus(2);
        }
        $entityManager->persist($conges);
        $entityManager->flush();

        return $this->redirectToRoute('conge_index');
    }


}
