<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\NoteService;
use App\Entity\Notification;
use App\Form\NoteServiceType;
use App\Repository\NoteServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/NoteService")
 */
class NoteServiceController extends AbstractController
{
    /**
     * @Route("/", name="note_service_index", methods={"GET"})
     */
    public function index(NoteServiceRepository $noteServiceRepository): Response
    {
        return $this->render('note_service/index.html.twig', [
            'note_services' => $noteServiceRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="note_service_new", methods={"GET","POST"})
     */
    public function new(Request $request, Security $security): Response
    {
        $noteService = new NoteService();
        $form = $this->createForm(NoteServiceType::class, $noteService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $responsable = $security->getUser();
            $noteService->setResponsable($responsable);
            //Recuperation de tous les employé

            $employes = $entityManager->getRepository(Employe::class)->findAll();
            foreach ($employes as $employe) {
                // Ajouter tous les employés sauf le responsable
                if ($employe != $responsable) {
                    $noteService->addEmploye($employe);

                    // **Ajout de la notification**
                    $notification = new Notification();
                    $notification->setEmploye($employe);
                    $notification->setMessage("Nouvelle note de service enregistrée");
                    $notification->setCreatedAt(new \DateTime());
                    $notification->setIsRead(false);
                    $notification->setLien($this->generateUrl('note_service_suivi', ['id' => $noteService->getId()]));
                    $entityManager->persist($notification);
                    $entityManager->flush();
                }
            }
            $entityManager->persist($noteService);
            $entityManager->flush();



            return $this->redirectToRoute('note_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note_service/new.html.twig', [
            'note_service' => $noteService,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/SuiviNote", name="note_service_suivi", methods={"GET"})
     */
    public function suiviNote(Security $security,NoteServiceRepository $noteServiceRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $security->getUser();
        $noteService = $noteServiceRepository->findNotesByEmployes($employe);

        return $this->render('note_service/suivi.html.twig', [
            'note_services' => $noteService,
        ]);
    }


    /**
     * @Route("/{id}", name="note_service_show", methods={"GET"})
     */
    public function show(NoteService $noteService): Response
    {
        return $this->render('note_service/show.html.twig', [
            'note_service' => $noteService,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="note_service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NoteService $noteService): Response
    {
        $form = $this->createForm(NoteServiceType::class, $noteService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('note_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note_service/edit.html.twig', [
            'note_service' => $noteService,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="note_service_delete", methods={"POST"})
     */
    public function delete(Request $request, NoteService $noteService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $noteService->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($noteService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('note_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
