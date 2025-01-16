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

class EmployeController extends AbstractController
{
    /**
     * @Route("/{_locale}/employe", name="employe")
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
           
        }
        return $this->render('employe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
