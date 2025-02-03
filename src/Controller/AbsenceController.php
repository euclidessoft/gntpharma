<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\ReponseAbsence;
use App\Form\AbsenceType;
use App\Form\ReponseAbsenceType;
use App\Repository\AbsenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @Route("/{_locale}/absence")
 */
class AbsenceController extends AbstractController
{
    /**
     * @Route("/", name="absence_index", methods={"GET"})
     */
    public function index(AbsenceRepository $absenceRepository): Response
    {
        return $this->render('absence/admin/index.html.twig', [
            'absences' => $absenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/Suivi", name="absence_suivi", methods={"GET"})
     */
    public function absence(AbsenceRepository $absenceRepository, Security $security): Response
    {
        $employe = $this->getUser();
        $absences = $this->getDoctrine()->getRepository(Absence::class)->findBy(['employe' => $employe]);
        return $this->render('absence/absence.html.twig', [
            'absences' => $absences,
        ]);
    }

    /**
     * @Route("/new", name="absence_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $absence = new Absence();
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $absence->setJustifier(false);
            $absence->setStatus(0);
            $entityManager->persist($absence);
            $entityManager->flush();

            return $this->redirectToRoute('absence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('absence/admin/new.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="absence_show", methods={"GET"})
     */
    public function show(Absence $absence): Response
    {
        $justificatif = $absence->getReponseAbsences();
        return $this->render('absence/admin/show.html.twig', [
            'absence' => $absence,
            'justificatif' => $justificatif, 
        ]);
    }

    /**
     * @Route("/{id}/edit", name="absence_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Absence $absence): Response
    {
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('absence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('absence/admin/edit.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="absence_delete", methods={"POST"})
     */
    public function delete(Request $request, Absence $absence): Response
    {
        if ($this->isCsrfTokenValid('delete' . $absence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($absence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('absence_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/justifier/{id}", name="absence_justifier", methods={"GET", "POST"})
     */
    public function reponse(Absence $absence, Request $request): Response
    {
        $justificatif = new ReponseAbsence();
        $justificatif->setAbsence($absence);
        $form = $this->createForm(ReponseAbsenceType::class, $justificatif);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $form->get('file')->getData();
            if ($file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFileName = $originalName . '-' . uniqid() . '.' . $file->guessExtension();
                $fileDirectory = $this->getParameter('justificatifs_directory');
                try {
                    $file->move($fileDirectory, $newFileName);
                } catch (\Throwable $th) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier.');
                    return $this->redirectToRoute('absence_justifier', ['id' => $absence->getId()]);
                }
                $justificatif->setFile($newFileName);
            }

            $absence->setStatus(1);

            $entityManager->persist($justificatif);
            $entityManager->flush();
            return $this->redirectToRoute('absence_suivi');
        }
        return $this->render("absence/justificatif.html.twig", [
            'form' => $form->createView(),
        ]);
    }
}
