<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\User;
use App\Form\EmployeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/{_locale}/Employe")
 */
class EmployeController extends AbstractController
{
    /**
     * @Route("/", name="employe_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $employe = $entityManager->getRepository(Employe::class)->findAll();
        return $this->render('employe/index.html.twig',[
            'employe' => $employe,
        ]);
    }




     /**
      * @Route("/new", name="employe_new", methods={"GET","POST"})
      */
    public function new(Request $request,UserPasswordEncoderInterface $encoder): Response
    {

        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();

            
            $hashpass = $encoder->encodePassword($employe, 'Passer2023');
            $employe->setPassword($hashpass);
            $employe->setUsername($employe->getNom());
            $employe->setRoles(["ROLE_EMPLOYE"]);
            $employe->setFonction("Employé");
            $employe->setHireDate($employe->getHireDate());
            
        
            $entityManager->persist($employe);
            $entityManager->flush();

            $this->addFlash('success', 'Employé créé avec success');
            return $this->redirectToRoute("employe_index");
           
        }
        return $this->render('employe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
