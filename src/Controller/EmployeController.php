<?php

namespace App\Controller;

use App\Entity\Calendrier;
use App\Entity\Employe;
use App\Entity\PosteEmploye;
use App\Form\EmployeType;
use App\Repository\DepartementRepository;
use App\Repository\PosteRepository;
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
        return $this->render('employe/index.html.twig', [
            'employe' => $employe,
        ]);
    }



    /**
     * @Route("/new", name="employe_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {

        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $poste = $employe->getPoste();
            if ($poste->getType() == true) {
                //on cherche si le pose est deja attribue
                $userposte = $entityManager->getRepository(PosteEmploye::class)->findOneBy(['poste' => $poste, 'datefin' => null]);
                if ($userposte) {
                    $this->addFlash('notice', 'Ce poste est unique et est déjà attribué à un employé.');
                    return $this->redirectToRoute('employe_new');
                }
            }
            $posteEmploye = new PosteEmploye();
            $hashpass = $encoder->encodePassword($employe, 'Passer2023');
            $employe->setPassword($hashpass);
            $employe->setUsername($employe->getNom());
            switch ($employe->getPoste()->getNom()) {
                case 'Administrateur': {
                    $employe->setRoles(['ROLE_ADMIN']);
                    break;
                }
                case 'Financier': {
                    $employe->setRoles(['ROLE_FINANCE']);
                    break;
                }
                case 'RH': {
                    $employe->setRoles(['ROLE_RH']);
                    break;
                }
                case 'EMPLOYER SIMPLE': {
                    $employe->setRoles(['ROLE_EMPLOYER']);
                    break;
                }
                case 'Gestionnaire de stock': {
                    $employe->setRoles(['ROLE_STOCK']);
                    break;
                }
                case 'Livreur': {
                    $employe->setRoles(['ROLE_LIVREUR']);
                    $employe->setLivreur(true);
                    break;
                }
            }
            $employe->setStatus(false);
            $employe->setHireDate($employe->getHireDate());
            $employe->setNombreJoursConges(30);

            $posteEmploye->setDatedebut(new \DateTime());
            $posteEmploye->setDatefin(null);
            $posteEmploye->setPoste($employe->getPoste());
            $posteEmploye->setEmploye($employe);

            //Calcul de la date debut de conges
            $nbreJoursConges = $employe->getNombreJoursConges();
            $dateDebutConges = (clone $employe->getHireDate())->modify('+11 months');
            $dateFinConges = (clone $dateDebutConges)->modify('+' .$nbreJoursConges. ' days');
            $calendrier = new Calendrier();
            $calendrier->setEmploye($employe);
            $calendrier->setDateDebut($dateDebutConges);
            $calendrier->setDateFin($dateFinConges);
//            dd($calendrier,$dateDebutConges,$dateFinConges,$nbreJoursConges);


            $entityManager->persist($posteEmploye);
            $entityManager->persist($employe);
            $entityManager->persist($calendrier);
            $entityManager->flush();

            $this->addFlash('notice', 'Employé créé avec succès');
            return $this->redirectToRoute("employe_index");
        }
        return $this->render('employe/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/toggle-status", name="employe_toggle_status", methods={"POST"})
     */
    public function toggleStatus(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        //verification du token csrf
        if (!$this->isCsrfTokenValid('toggle' . $employe->getId(), $request->request->get('_token'))) {
            $this->addFlash('notice', 'Token CSRF invalide');
            return $this->redirectToRoute('employe_index');
        }

        if ($employe->getStatus()) {
            $employe->setStatus(false);
            $this->addFlash('notice', 'Employé désativé');
        } else {
            $employe->setStatus(true);
            $this->addFlash('notice', 'Employé activé');
        }

        $entityManager->persist($employe);
        $entityManager->flush();
        return $this->redirectToRoute('employe_index');
    }

    /**
     * @Route("/config", name="employe_congif", methods={"GET"})
     */
    public function config()
    {
        return $this->render('employe/config.html.twig');
    }

 
}
