<?php
// src/Controller/NotificationController.php
namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/{_locale}/notifications")
 */
class NotificationController extends AbstractController
{

//
//    /**
//     * @Route("/check", name="notification_check")
//     */
//    public function notification(NotificationRepository $notificationRepository, Security $security): JsonResponse
//    {
//        // Vérifier si un utilisateur est connecté
//        $employe = $security->getUser();
//        if (!$employe) {
//            return $this->json(['error' => 'Utilisateur non connecté'], 401);
//        }
//
//        // Temps max d'attente pour le long polling (en secondes)
//        $timeOut = 30;
//        $startTime = time();
//
//        // Boucle infinie pour long polling
//        while (true) {
//            // Récupérer les notifications non lues pour l'utilisateur
//            $notifications = $notificationRepository->findUnReadByEmploye($employe);
//
//            // Si des notifications ont été trouvées, on les renvoie
//            if (!empty($notifications)) {
//                return $this->json([
//                    'count' => count($notifications),
//                    'notifications' => array_map(fn($n) => [
//                        'id' => $n->getId(),
//                        'message' => $n->getMessage(),
//                        'createdAt' => $n->getCreatedAt()->format('d/m/Y H:i'),
//                        'lien' => $n->getLien(),
//                    ], $notifications)
//                ]);
//            }
//
//            // Si le timeout est dépassé sans notifications, renvoyer une réponse vide
//            if (time() - $startTime > $timeOut) {
//                return $this->json(['count' => 0, 'notifications' => []]);
//            }
//
//            // Attente de 1 seconde avant de refaire une vérification
//            sleep(1);
//        }
//    }
//
//    /**
//     * @Route("/Read/{id}", name="notification_read", methods={"POST"})
//     */
//    public function read(Notification $notification): JsonResponse
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//        if ($notification->getIsRead()) {
//            return $this->json(['message' => 'La notification est déjà lue'], 400);
//        }
//
//        $notification->setIsRead(true);
//        // Sauvegarder la notification mise à jour
//        $entityManager->persist($notification);
//        $entityManager->flush();
//        return $this->json(['message' => 'Notification marquée comme lue']);
//    }
}


?>