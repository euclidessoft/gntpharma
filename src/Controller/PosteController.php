<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Poste;
use App\Entity\PosteEmploye;
use App\Form\PosteType;
use App\Repository\PosteEmployeRepository;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/Poste")
 */
class PosteController extends AbstractController
{
    /**
     * @Route("/", name="poste_index", methods={"GET"})
     */
    public function index(PosteRepository $posteRepository): Response
    {
        return $this->render('poste/index.html.twig', [
            'postes' => $posteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="poste_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poste);
            $entityManager->flush();

            return $this->redirectToRoute('poste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('poste/new.html.twig', [
            'poste' => $poste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="poste_show", methods={"GET"})
     */
    public function show(Poste $poste,EntityManagerInterface $entityManager): Response
    {
        $employes = $entityManager->getRepository(Employe::class)->findBy(['poste' => $poste]);
        $attribuer = $poste->getEmployes()->isEmpty();
        $unique = $poste->getType() == 'unique';

        return $this->render('poste/show.html.twig', [
            'poste' => $poste,
            'employes' => $employes,
            'attribuer' => $attribuer,
            'unique' => $unique
        ]);
    }


    /**
     * @Route("/{id}/attribuer", name="poste_attribuer", methods={"GET","POST"})
     */
    public function attribuerEmployes(Poste $poste,Request $request ,EntityManagerInterface $entityManager): Response
    {
        $employes = $entityManager->getRepository(Employe::class)->findBy(['poste' => null]);
        if($request->isMethod('POST')){
            //Recuperation des employe selectionner
            $employesSelect = $request->get('employes',[]);
               

                if($poste->getType() == true){
                    $employeExistant = $entityManager->getRepository(Employe::class)->findOneBy(['poste' => $poste]);
                    
                    if($employeExistant){
                        $this->addFlash('error', 'Ce poste est unique et est déjà attribué.');
                        return $this->redirectToRoute('poste_attribuer', ['id' => $poste->getId()]);
                    }

                    if(count($employesSelect) > 1){
                        $this->addFlash('error','Ce poste est unique et ne peut être attribué qu’à un seul employé.');
                        return $this->redirectToRoute('poste_attribuer', ['id' => $poste->getId()]);
                    }
                }
                //Attribuer les employe selectionnez au poste

            foreach($employesSelect as $employe)
            {
                $employeposte = $entityManager->getRepository(Employe::class)->find($employe);
                if($employeposte){
                    $employeposte->setPoste($poste);
                    $posteEmploye = new PosteEmploye();
                    $posteEmploye->setEmploye($employeposte);
                    $posteEmploye->setDatedebut(new \DateTime());
                    $posteEmploye->setPoste($poste);
                }
            }

            $entityManager->flush();

            // Message de succès
            $this->addFlash('success', 'Les employés ont été affectés au poste avec succès.');
    
            return $this->redirectToRoute('poste_show', ['id' => $poste->getId()]);

        }
        return $this->render('poste/attribuerposte.html.twig', [
            'poste' => $poste,
            'employes' => $employes,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="poste_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Poste $poste): Response
    {
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('poste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('poste/edit.html.twig', [
            'poste' => $poste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="poste_delete", methods={"POST"})
     */
    public function delete(Request $request, Poste $poste): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poste->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($poste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('poste_index', [], Response::HTTP_SEE_OTHER);
    }
}
