<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\MessageRecipient;
use App\Entity\MessageReply;
use App\Form\MessageReplyType;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/messages")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="message")
     */
    public function index(EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $user = $this->getUser();
            $messageRecipients = $em->getRepository(MessageRecipient::class)
                ->findBy(['recipient' => $user], ['id' => 'DESC']);

            return $this->render('message/index.html.twig', [
                'messageRecipients' => $messageRecipients,
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
     * @Route("/send", name="send")
     */
    public function send(Request $request, EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $message = new Message;
            $unread = $em->getRepository(MessageRecipient::class)
                ->findBy(['isRead' => false, 'recipient' => $this->getUser()], ['id' => 'DESC']);

            $form = $this->createForm(MessageType::class, $message);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $message->setSender($this->getUser());

                $recipients = $form->get('recipients')->getData();

                foreach ($recipients as $userRecipient) {
                    $messageRecipient = new MessageRecipient();
                    $messageRecipient->setRecipient($userRecipient);
                    $messageRecipient->setIsRead(false);
                    $messageRecipient->setSender($this->getUser());
                    $message->addRecipient($messageRecipient);
                }
                $em->persist($message);
                $em->flush();

                $this->addFlash("message", "Message envoyé avec succès.");
                return $this->redirectToRoute("received");
            }

            return $this->render("message/send.html.twig", [
                "form" => $form->createView(),
                'unread' => $unread,
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
     * @Route("/received", name="received")
     */
    public function received(EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $user = $this->getUser();
            $messageRecipients = $em->getRepository(MessageRecipient::class)
                ->findBy(['recipient' => $user], ['id' => 'DESC']);
            $unread = $em->getRepository(MessageRecipient::class)
                ->findBy(['isRead' => false, 'recipient' => $this->getUser()], ['id' => 'DESC']);

            return $this->render('message/received.html.twig', [
                'messages' => $messageRecipients,
                'unread' => $unread,
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
     * @Route("/sent", name="sent")
     */
    public function sent(EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $user = $this->getUser();
            $messageRecipients = $em->getRepository(MessageRecipient::class)
                ->findBy(['sender' => $user], ['id' => 'DESC']);
            $unread = $em->getRepository(MessageRecipient::class)
                ->findBy(['isRead' => false, 'recipient' => $this->getUser()], ['id' => 'DESC']);


            return $this->render('message/sent.html.twig', [
                'messages' => $messageRecipients,
                'unread' => $unread,
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
     * @Route("/read/{id}", name="read")
     */
    public function read(MessageRecipient $message, EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $unread = $em->getRepository(MessageRecipient::class)
                ->findBy(['isRead' => false, 'recipient' => $this->getUser()], ['id' => 'DESC']);

            $message->setIsRead(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            return $this->render('message/read.html.twig', ["message" => $message, 'unread' => $unread,]);
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
     * @Route("/readSend/{id}", name="readSend")
     */
    public function readsend(Message $message, EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $unread = $em->getRepository(MessageRecipient::class)
                ->findBy(['isRead' => false, 'recipient' => $this->getUser()], ['id' => 'DESC']);
//        $message->setIsRead(true);
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($message);
//        $em->flush();

            return $this->render('message/readsend.html.twig', ["message" => $message, 'unread' => $unread,]);
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
     * @Route("/Replay/{id}", name="reply")
     */
    public function reply(MessageRecipient $message, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $unread = $em->getRepository(MessageRecipient::class)
                ->findBy(['isRead' => false, 'recipient' => $this->getUser()], ['id' => 'DESC']);
            $messagereply = new MessageReply();
            $form = $this->createForm(MessageReplyType::class, $messagereply);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $messagereply->setSender($this->getUser());
                $messagereply->setMessage($message);

//            $recipients = $form->get('recipients')->getData();
//
//            foreach ($recipients as $userRecipient) {
//                $messageRecipient = new MessageRecipient();
//                $messageRecipient->setRecipient($userRecipient);
//                $messageRecipient->setIsRead(false);
//                $messageRecipient->setSender($this->getUser());
//                $messagereply->addRecipient($messageRecipient);
//            }
                $em->persist($messagereply);
                $em->flush();

                $this->addFlash("message", "Message envoyé avec succès.");
                return $this->redirectToRoute("message");
            }

            return $this->render('message/reply.html.twig', [
                "form" => $form->createView(),
                "message" => $message,
                'unread' => $unread,
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
// suppression message cote destinatataire

    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(MessageRecipient $message, EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            if ($message->getRecipient() !== $this->getUser()) {
                $this->addFlash('notice',"Vous ne pouvez pas supprimer ce message.");
                return $this->redirectToRoute('received');
            }

            $message->delete();
            $em->flush();
            $this->addFlash('notice',"message supprimé.");
            return $this->redirectToRoute('received');
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
     * @Route("/{id}/destdelete", name="destdelete")
     */
    public function destdelete(Message $message, EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            if ($message->getSender() !== $this->getUser()) {
                $this->addFlash('notice',"Vous ne pouvez pas supprimer ce message.");
                return $this->redirectToRoute('received');
            }

            $message->delete();
            $em->flush();
            $this->addFlash('notice',"message supprimé.");
            return $this->redirectToRoute('received');
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
     * @Route("/trash", name="trash")
     */
    public function trash(EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            $messages = $em->getRepository(MessageRecipient::class)->trash($this->getUser()->getId());
            $messagesent = $em->getRepository(Message::class)->trash($this->getUser()->getId());

            $unread = $em->getRepository(MessageRecipient::class)
                ->findBy(['isRead' => false, 'recipient' => $this->getUser()], ['id' => 'DESC']);
            return $this->render('message/trash.html.twig', [
                'messages' => $messages,
                'messagesent' => $messagesent,
                'unread' => $unread,
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
     * @Route("/{id}/restore", name="restore")
     */
    public function restore(Message $message, EntityManagerInterface $em): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER')) {
            if (!$message->isDeleted()) {
                return $this->redirectToRoute('trash');
            }

            $message->restore();
            $em->flush();

            return $this->redirectToRoute('inbox');
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
    // fin destinataire
}
