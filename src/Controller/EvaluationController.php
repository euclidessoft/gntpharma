<?php

namespace App\Controller;

use App\Entity\CritereEvaluation;
use App\Entity\Employe;
use App\Entity\Evaluation;
use App\Entity\EvaluationDetail;
use App\Form\EvaluationType;
use App\Repository\EvaluationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/Evaluation")
 */
class EvaluationController extends AbstractController
{
    /**
     * @Route("/", name="evaluation_index", methods={"GET"})
     */
    public function index(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="evaluation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $criteres = $this->getDoctrine()->getRepository(CritereEvaluation::class)->findAll();
        $employes = $this->getDoctrine()->getRepository(Employe::class)->findAll();

        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employeSelect = $request->request->get('employe');
            $dateEvaluation = new \DateTime($request->request->get('dateEvaluation'));
            $notes = $request->request->get('notes');
            $commentaires = $request->request->get('commentaires');
            $employe = $entityManager->getRepository(Employe::class)->find($employeSelect);

            $evaluation->setDateEvaluation($dateEvaluation);
            $evaluation->setEmploye($employe);

            $totalNotes = 0;
            $nbNotes = 0;

            foreach ($notes as $critereId => $note) {
                $critere = $entityManager->getRepository(CritereEvaluation::class)->find($critereId);
                $evaluationDetail = new EvaluationDetail();
                $evaluationDetail->setCritereEvaluation($critere);
                $evaluationDetail->setNote((int)$note);
                $evaluationDetail->setEvaluation($evaluation);
                if (isset($commentaires[$critereId])) {
                    $evaluationDetail->setCommentaire($commentaires[$critereId]);
                }
                $evaluation->addEvaluationDetail($evaluationDetail);
                $entityManager->persist($evaluationDetail);

                $totalNotes += (int)$note;
                $nbNotes++;
            }

            $moyenne = ($nbNotes > 0) ? ($totalNotes / $nbNotes) : 0;
            $evaluation->setMoyenne($moyenne);
            $evaluation->addEvaluationDetail($evaluationDetail);
            $entityManager->persist($evaluation);
            $entityManager->flush();

            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('evaluation/evaluation.html.twig', [
            'employes' => $employes,
            'form' => $form->createView(),
            'criteres' => $criteres,
        ]);
    }

    /**
     * @Route("/{id}", name="evaluation_show", methods={"GET"})
     */
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }


    /**
     * @Route("/Suivi", name="evaluation_suivi", methods={"GET"})
     */
    public function suivi(Security $security): Response
    {
        $employe = $security->getUser();
        $evaluation = $this->getDoctrine()->getRepository(Evaluation::class)->findBy(['employe' => $employe]);
        return $this->render('evaluation/suivi.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="evaluation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Evaluation $evaluation): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evaluation_delete", methods={"POST"})
     */
    public function delete(Request $request, Evaluation $evaluation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evaluation_index', [], Response::HTTP_SEE_OTHER);
    }
}
