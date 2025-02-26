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
        $user = $this->getUser();
        $messageRecipients = $em->getRepository(MessageRecipient::class)
            ->findBy(['recipient' => $user], ['id' => 'DESC']);

        return $this->render('message/index.html.twig', [
            'messageRecipients' => $messageRecipients,
        ]);
    }

    /**
     * @Route("/send", name="send")
     */
    public function send(Request $request, EntityManagerInterface $em): Response
    {
        $message = new Message;
        $unread = $em->getRepository(MessageRecipient::class)
            ->findBy(['deletedAt' => null], ['id' => 'DESC']);

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
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
            return $this->redirectToRoute("message");
        }

        return $this->render("message/send.html.twig", [
            "form" => $form->createView(),
            'unread' => $unread,
        ]);
    }

    /**
     * @Route("/received", name="received")
     */
    public function received(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $messageRecipients = $em->getRepository(MessageRecipient::class)
            ->findBy(['recipient' => $user], ['id' => 'DESC']);
        $unread = $em->getRepository(MessageRecipient::class)
            ->findBy(['deletedAt' => null], ['id' => 'DESC']);

        return $this->render('message/received.html.twig', [
            'messages' => $messageRecipients,
            'unread' => $unread,
        ]);
    }


    /**
     * @Route("/sent", name="sent")
     */
    public function sent(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $messageRecipients = $em->getRepository(MessageRecipient::class)
            ->findBy(['sender' => $user], ['id' => 'DESC']);
        $unread = $em->getRepository(MessageRecipient::class)
            ->findBy(['deletedAt' => null], ['id' => 'DESC']);



        return $this->render('message/sent.html.twig', [
            'messages' => $messageRecipients,
            'unread' => $unread,
        ]);
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(MessageRecipient $message, EntityManagerInterface $em): Response
    {
        $unread = $em->getRepository(MessageRecipient::class)
            ->findBy(['deletedAt' => null], ['id' => 'DESC']);

        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('message/read.html.twig', ["message" => $message,  'unread' => $unread,]);
    }

    /**
     * @Route("/readSend/{id}", name="readSend")
     */
    public function readsend(Message $message, EntityManagerInterface $em): Response
    {
        $unread = $em->getRepository(MessageRecipient::class)
            ->findBy(['deletedAt' => null], ['id' => 'DESC']);
//        $message->setIsRead(true);
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($message);
//        $em->flush();

        return $this->render('message/readsend.html.twig', ["message" => $message,  'unread' => $unread,]);
    }

    /**
     * @Route("/Replay/{id}", name="reply")
     */
    public function reply(MessageRecipient $message, Request $request, EntityManagerInterface $em): Response
    {
        $unread = $em->getRepository(MessageRecipient::class)
            ->findBy(['deletedAt' => null], ['id' => 'DESC']);
        $messagereply = new MessageReply();
        $form = $this->createForm(MessageReplyType::class, $messagereply);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
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

        return $this->render('message/reply.html.twig' ,[
        "form" => $form->createView(),
            "message" => $message,
            'unread' => $unread,
        ]);
    }
// suppression message cote destinatataire
    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function delete(MessageRecipient $message, EntityManagerInterface $em): Response
    {
        if ($message->getRecipient() !== $this->getUser()) {
            $this->addFlash("Vous ne pouvez pas supprimer ce message.");
        }

        $message->delete();
        $em->flush();

        return $this->redirectToRoute('received');
    }

    /**
     * @Route("/trash", name="trash")
     */
    public function trash(EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(MessageRecipient::class)->findBy(['deletedAt' => null]);
        $unread = $em->getRepository(MessageRecipient::class)
            ->findBy(['deletedAt' => null], ['id' => 'DESC']);
        dd($messages);
        return $this->render('message/trash.html.twig', [
            'messages' => $messages,
            'unread' => $unread,
        ]);
    }

    /**
     * @Route("/{id}/restore", name="restore")
     */
    public function restore(Message $message, EntityManagerInterface $em): Response
    {
        if (!$message->isDeleted()) {
            return $this->redirectToRoute('trash');
        }

        $message->restore();
        $em->flush();

        return $this->redirectToRoute('inbox');
    }
    // fin destinataire
}
