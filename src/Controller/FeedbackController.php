<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/Feedback")
 */
class FeedbackController extends AbstractController
{
    /**
     * @Route("/", name="feedback_index", methods={"GET"})
     */
    public function index(FeedbackRepository $feedbackRepository): Response
    {
        return $this->render('feedback/admin/index.html.twig', [
            'feedback' => $feedbackRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="feedback_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $feedback->setCreatedAt(new \DateTime());
            $feedback->setEmploye($employe);

            $entityManager->persist($feedback);
            $entityManager->flush();

            return $this->redirectToRoute('feedback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('feedback/admin/new.html.twig', [
            'feedback' => $feedback,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/Suivi", name="feedback_suivi", methods={"GET"})
     */
    public function suivi(Security $security): Response
    {
        $employe = $security->getUser();
        $feedback = $this->getDoctrine()->getRepository(Feedback::class)->findBy(['employe' => $employe]);

        return $this->render("feedback/index.html.twig", [
            'feedback' => $feedback,
        ]);
    }

    /**
     * @Route("/Suivi/{id}", name="feedback_suivi_show", methods={"GET"})
     */
    public function suiviShow(Feedback $feedback): Response
    {
            return $this->render("feedback/show.html.twig", [
            'feedback' => $feedback,
        ]);
    }


    /**
     * @Route("/{id}", name="feedback_show", methods={"GET"})
     */
    public function show(Feedback $feedback): Response
    {
        return $this->render('feedback/admin/show.html.twig', [
            'feedback' => $feedback,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="feedback_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Feedback $feedback): Response
    {
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('feedback_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('feedback/admin/edit.html.twig', [
            'feedback' => $feedback,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="feedback_delete", methods={"POST"})
     */
    public function delete(Request $request, Feedback $feedback): Response
    {
        if ($this->isCsrfTokenValid('delete' . $feedback->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($feedback);
            $entityManager->flush();
        }

        return $this->redirectToRoute('feedback_index', [], Response::HTTP_SEE_OTHER);
    }
}
