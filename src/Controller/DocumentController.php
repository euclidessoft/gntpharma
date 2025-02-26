<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Employe;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("{_locale}/Document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document_index", methods={"GET"})
     */
    public function index(EmployeRepository $employeRepository): Response
    {
        $employeDocument = $employeRepository->findByEmployeWithDocument();
        return $this->render('document/admin/index.html.twig', [
            'employes' => $employeDocument,
        ]);
    }

    /**
     * @Route("/Suivi", name="document_suivi", methods={"GET"})
     */
    public function suivi(Security $security,EmployeRepository $employeRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $employe = $security->getUser();
        $employeDocument = $employeRepository->findByEmployeWithForUser($employe);
        return $this->render('document/index.html.twig', [
            'employes' => $employeDocument,
        ]);
    }


    /**
     * @Route("/new", name="document_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employe = $document->getEmploye();
            $fileName = $form->get('fileName')->getData();
            $files = $form->get('filePath')->getData();
            if ($files) {
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($files as $file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = uniqid() . '.' . $file->guessExtension();
                    try {
                        $file->move(
                            $this->getParameter('documents_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('error', 'Erreur lors de l\'upload du fichier');
                        return $this->redirectToRoute('document_new');
                    }

                    $document = new Document();
                    $document->setEmploye($employe);
                    $document->setFilePath($newFilename);
                    $document->setCreatedAt(new \DateTime());
                    $document->setFileName($fileName);

                    $entityManager->persist($document);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/admin/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_show", methods={"GET"})
     */
    public function show(Employe $employe): Response
    {
        return $this->render('document/admin/show.html.twig', [
            'employe' => $employe,
            'documents' => $employe->getDocuments(), // Récupérer tous les documents de l'employé
        ]);
    }

    /**
     * @Route("Suivi/Show/{id}", name="document_suivi_show", methods={"GET"})
     */
    public function suiviShow(Document $document)
    {
        $employe = $document->getEmploye();
        return $this->render('document/show.html.twig', [
            'employe' => $employe,
            'documents' => $employe->getDocuments(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('document/admin/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_delete", methods={"POST"})
     */
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete' . $document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_index', [], Response::HTTP_SEE_OTHER);
    }
}
