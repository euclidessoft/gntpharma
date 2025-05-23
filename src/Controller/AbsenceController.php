<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Decision;
use App\Entity\DemandeExplication;
use App\Entity\Notification;
use App\Entity\ReponseAbsence;
use App\Entity\Sanction;
use App\Form\AbsenceType;
use App\Form\DecisionType;
use App\Form\ReponseAbsenceType;
use App\Repository\AbsenceRepository;
use App\Repository\DemandeExplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/{_locale}/Absence")
 */
class AbsenceController extends AbstractController
{

    /**
     * @Route("/", name="absence_index", methods={"GET"})
     */
    public function index(AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $absences = $absenceRepository->findBy([], ['dateAbsence' => 'DESC']);

            return $this->render('absence/admin/index.html.twig', [
                'absences' => $absences,
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
     * @Route("/new", name="absence_new", methods={"GET","POST"})
     */
    public function new(Request $request, AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $absence = new Absence();
            $form = $this->createForm(AbsenceType::class, $absence);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $employe = $absence->getEmploye();

                // Définir la date du jour pour la date de début et de fin (journée entière)
                $dateDebut = new \DateTime(); // Date d'aujourd'hui
                $dateFin = clone $dateDebut; // Date de fin égale à la date de début (journée entière)

                $absence->setDateAbsence($dateDebut);
                $absence->setDateFin($dateFin);

                // Récupérer la dernière absence enregistrée pour cet employé
                $dernierAbsence = $absenceRepository->findOneBy(
                    ['employe' => $employe],
                    ['dateFin' => 'DESC']
                );

                if ($dernierAbsence) {
                    $dernierDateFin = $dernierAbsence->getDateFin();
                    $dernierDateFin->modify('+1 day');
                    if ($dernierDateFin->format('Y-m-d') === $dateDebut->format('Y-m-d')) {
                        // prolongement absence jour suivant
                        $dernierAbsence->setDateFin($dateFin);
                        $entityManager->flush();
                        $this->addFlash('notice', 'absence prolongée');
                        return $this->redirectToRoute('absence_index', [], Response::HTTP_SEE_OTHER);
                    }

                    // Vérifier si la nouvelle absence chevauche la précédente
                    if ($dateDebut <= $dernierAbsence->getDateFin()) {
                        // Si la nouvelle absence commence avant ou pendant la dernière absence
                        if ($absence->getDateFin() > $dernierAbsence->getDateFin()) {
                            $dernierAbsence->setDateFin($absence->getDateFin()); // Met à jour la date de fin
                            $entityManager->flush();
                        }
                        $this->addFlash('notice', 'absence deja enregistré');
                        return $this->redirectToRoute('absence_index', [], Response::HTTP_SEE_OTHER);
                    }
                }

                $entityManager->persist($absence);
                $entityManager->flush();

//                // **Ajout de la notification**
//                $notification = new Notification();
//                $notification->setEmploye($employe);
//                $notification->setMessage("Nouvelle absence enregistrée du " .$dateFin->format('d/m/Y') . " au " .$dateFin->format('d/m/Y'));
//                $notification->setCreatedAt(new \DateTime());
//                $notification->setIsRead(false);
//                $notification->setLien($this->generateUrl('absence_detail', ['id' => $absence->getId()]));
//                $entityManager->persist($notification);
//                $entityManager->flush();


                return $this->redirectToRoute('absence_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('absence/admin/new.html.twig', [
                'form' => $form->createView(),
                'absence' => $absence,
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
     * @Route("/{id}/Show", name="absence_show", methods={"GET"})
     */
    public function show(Absence $absence): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            return $this->render('absence/admin/show.html.twig', [
                'absences' => $absence,
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
     * @Route("/Suivi", name="absence_suivi", methods={"GET"})
     */
    public function suivi(Security $security): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $employe = $this->getUser();
            $absences = $this->getDoctrine()->getRepository(Absence::class)->findAbsenceAll($employe);
            return $this->render('absence/absence.html.twig', [
                'absences' => $absences,
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
     * @Route("/justifier/{id}", name="absence_justifier", methods={"GET", "POST"})
     */
    public function reponse(Absence $absence, Request $request): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
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
                $absence->setStatus(true);

                $entityManager->persist($justificatif);
                $entityManager->flush();
                return $this->redirectToRoute('absence_suivi');
            }
            return $this->render("absence/justificatif.html.twig", [
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
     * @Route("/{id}/confirmer", name="absence_confirmer", methods={"GET", "POST"})
     */
    public function confirmer(Request $request, Absence $absence, Security $security)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $responsable = $security->getUser();

            if ($this->isCsrfTokenValid('confirmer' . $absence->getId(), $request->request->get('_token'))) {
                $absence->setJustifier(true);
                $absence->setResponsable($responsable);
                $absence->setDateAbsence(new \DateTime());
            }
            $entityManager->persist($absence);
            $entityManager->flush();
            return $this->redirectToRoute('absence_index');
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
     * @Route("/{id}/refuser", name="absence_refuser", methods={"GET","POST"})
     */
    public function refuser(Request $request, Absence $absence, Security $security): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $decision = new Decision();

            $form = $this->createForm(DecisionType::class, $decision);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $responsable = $security->getUser();
                $entityManager = $this->getDoctrine()->getManager();

                $typeDecision = $decision->getType();
                $decision->setDateDecision(new \DateTime());
                $decision->setAbsences($absence);
                $decision->setResponsable($responsable);
                $decision->setDateConfirm(new \DateTime());

                $absence->setJustifier(false);
                $absence->setResponsable($responsable);
                $absence->setDateConfirm(new \DateTime());

                $entityManager->persist($decision);

                if ($typeDecision == 'Demande d\'explication') {
                    $demandeExplication = new DemandeExplication();
                    $demandeExplication->setObjet('Absence non justifiée');
                    $demandeExplication->setDetails($form->get('demandes')->getData());
                    $demandeExplication->setDate(new \DateTime());
                    $demandeExplication->setDateIncident($decision->getAbsences()->getDateAbsence());
                    $demandeExplication->addEmploye($decision->getAbsences()->getEmploye());
                    $demandeExplication->setStatus(false);
                    $demandeExplication->setResponsable($responsable);
                    $decision->setExplication($demandeExplication);
                    $entityManager->persist($demandeExplication);
                } elseif ($typeDecision == 'Sanction') {
                    $employe = $decision->getAbsences()->getEmploye();
                    $nbreJoursConges = $employe->getNombreJoursConges();
                    $sanction = new Sanction();
                    $sanction->setDateCreation(new \DateTime());
                    $sanction->setCreatedAt(new \DateTime());
                    $sanction->setTypeSanction($decision->getTypeSanction());
                    $sanction->setEmploye($employe);
                    $typeSanction = $decision->getTypeSanction()->getNom();

                    if ($typeSanction === 'mis a pied') {
                        $dateDebut = $decision->getDateDebut();
                        $dateFin = $decision->getDateFin();
                        $decision->setDateDebut($dateDebut);
                        $decision->setDateFin($dateFin);
                        $sanction->setDateDebut($dateDebut);
                        $sanction->setDateFin($dateFin);
                        $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                        $sanction->setNombreJours($nombreJours);

                        $absence = new Absence();
                        $absence->setEmploye($decision->getAbsences()->getEmploye());
                        $absence->setDateAbsence($dateDebut);
                        $absence->setDateFin($dateFin);
                        $absence->setJustifier(true);
                        $absence->setStatus(1);
                        $entityManager->persist($absence);
                    } elseif ($typeSanction === 'Retenue sur les congés') {
                        $dateDebut = $decision->getDateDebut();
                        $dateFin = $decision->getDateFin();
                        $sanction->setDateDebut($dateDebut);
                        $sanction->setDateFin($dateFin);
                        $nombreJours = $dateDebut->diff($dateFin)->days + 1;
                        $sanction->setNombreJours($nombreJours);

                        if ($nbreJoursConges >= $nombreJours) {
                            $nbreJourRestant = $nbreJoursConges - $nombreJours;
                            $employe->setNombreJoursConges($nbreJourRestant);
                            //Defalquer les conges
                            $calendar = $employe->getCalendriers();
                            foreach ($calendar as $calendrier) {
                                $dateFinConges = (clone $calendrier->getDateDebut())->modify('+' . $nbreJourRestant . ' days');
                                $calendrier->setDateFin($dateFinConges);
                                $entityManager->persist($calendrier);
                            }
                        }
                    } elseif ($typeSanction === 'Ponction Salariale') {
                        if($absence->getDateAbsence() == $absence->getDateFin())
                            $sanction->setNombreJours( 1);
                        else
                            $sanction->setNombreJours( $absence->getDateAbsence()->diff($absence->getDateFin())->days);
                    }

                    $entityManager->persist($sanction);
                }

                $entityManager->flush();

                return $this->redirectToRoute('absence_index');
            }
            return $this->render("absence/admin/decision.html.twig", [
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
     * @Route("/Detail/{id}", name="absence_detail", methods={"GET"})
     */
    public function detail(Absence $absence): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            return $this->render('absence/show.html.twig', [
                'absence' => $absence,
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
     * @Route("/Suivi/Attente", name="absence_employe_attente")
     */
    public function accepter(Security $security, AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $absences = $absenceRepository->findAbsenceAttente($employe);
            return $this->render('absence/attente.html.twig', [
                'absences' => $absences,
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
     * @Route("/Suivi/Admin/Attente", name="absence_admin_attente")
     */
    public function attente(Security $security, AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_RH')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $absences = $absenceRepository->adminAttente();
            return $this->render('absence/admin/attente.html.twig', [
                'absences' => $absences,
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
     * @Route("/Suivi/Refuser", name="absence_employe_refuser")
     */
    public function RefuserAbsence(Security $security, AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $absences = $absenceRepository->findAbsenceRefuser($employe);
            return $this->render('absence/refuser.html.twig', [
                'absences' => $absences,
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
     * @Route("/Suivi/Admin/Refuser", name="absence_admin_refuser")
     */
    public function RefuserAdmin(Security $security, AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $absences = $absenceRepository->findBy(['status' => true, 'justifier' => true]);
            return $this->render('absence/admin/refuser.html.twig', [
                'absences' => $absences,
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
     * @Route("/Suivi/Accepter", name="absence_employe_accepter")
     */
    public function AccepterAbsence(Security $security, AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $absences = $absenceRepository->findAbsenceAccepeter($employe);
            return $this->render('absence/accepter.html.twig', [
                'absences' => $absences,
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
     * @Route("/Suivi/Admin/Accepter", name="absence_admin_accepter")
     */
    public function AccepterAdmin(Security $security, AbsenceRepository $absenceRepository): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->getDoctrine()->getManager();
            $employe = $security->getUser();
            $absences = $absenceRepository->findBy(['status' => 1, 'justifier' => true]);
            return $this->render('absence/admin/accepter.html.twig', [
                'absences' => $absences,
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


}
