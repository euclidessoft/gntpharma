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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $employe = $entityManager->getRepository(Employe::class)->findAll();
            return $this->render('employe/index.html.twig', [
                'employes' => $employe,
            ]);
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }


    /**
     * @Route("/manage", name="employe_manage", methods={"GET"})
     */
    public function manage(EntityManagerInterface $entityManager)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $employe = $entityManager->getRepository(Employe::class)->findAll();
            return $this->render('employe/manage.html.twig', [
                'employes' => $employe,
            ]);
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }


    /**
     * @Route("/new", name="employe_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {

            $employe = new Employe();
            $form = $this->createForm(EmployeType::class, $employe);
            $form->remove('password');
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                $poste = $employe->getPoste();
                if ($poste->getType() == true) {
                    //on cherche si le poste est deja attribue
                    $userposte = $entityManager->getRepository(PosteEmploye::class)->findOneBy(['poste' => $poste, 'datefin' => null]);
                    if ($userposte) {
                        $this->addFlash('notice', 'Ce poste est unique et est déjà attribué à un employé.');
                        return $this->redirectToRoute('employe_new');
                    }
                }
                $posteEmploye = new PosteEmploye();
                $hashpass = $encoder->encodePassword($employe, 'GNTPharma');
                $employe->setPassword($hashpass);
                $employe->setUsername($employe->getNom());
                switch ($employe->getFonction()) {
                    case 'ADMINISTRATEUR': {
                            $employe->setRoles(['ROLE_ADMIN']);
                            break;
                        }
                    case 'FINANCE': {
                            $employe->setRoles(['ROLE_FINANCE']);
                            break;
                        }
                    case 'RH': {
                            $employe->setRoles(['ROLE_RH']);
                            break;
                        }
                    case 'EMPLOYER': {
                            $employe->setRoles(['ROLE_EMPLOYER']);
                            break;
                        }
                    case 'STOCK': {
                            $employe->setRoles(['ROLE_STOCK']);
                            break;
                        }
                    case 'LIVREUR': {
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
                $dateFinConges = (clone $dateDebutConges)->modify('+' . $nbreJoursConges . ' days');
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
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    /**
     * @Route("/{id}/edit", name="employe_edit", methods={"POST","GET"})
     */
    public function edit(Request $request, Employe $employe): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->remove('password');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Employé modifié avec succès');
            return $this->redirectToRoute('employe_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('employe/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/toggle-status", name="employe_toggle_status", methods={"POST"})
     */
    public function toggleStatus(Request $request, Employe $employe, EntityManagerInterface $entityManager): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            //verification du token csrf
            if (!$this->isCsrfTokenValid('toggle' . $employe->getId(), $request->request->get('_token'))) {
                $this->addFlash('notice', 'Token CSRF invalide');
                return $this->redirectToRoute('employe_manage');
            }

            if ($employe->getStatus()) {
                $employe->setStatus(false);
                $employe->setEnabled(false);
                $this->addFlash('notice', 'Utilisateur désativé');
            } else {
                $employe->setStatus(true);
                $employe->setEnabled(true);
                $this->addFlash('notice', 'Utilisateur activé');
            }

            $entityManager->persist($employe);
            $entityManager->flush();
            return $this->redirectToRoute('employe_manage');
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    /**
     * @Route("/config", name="employe_congif", methods={"GET"})
     */
    public function config()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            return $this->render('employe/config.html.twig');
        } else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    /**
     * @Route("/Show/{id}", name="employe_show", methods={"GET"})
     */
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }
    
}
