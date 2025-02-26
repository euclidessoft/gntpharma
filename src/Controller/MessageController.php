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
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
    }


    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('message/sent.html.twig');
    }

    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(MessageRecipient $message): Response
    {
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('message/read.html.twig', compact("message"));
    }

    /**
     * @Route("/readSend/{id}", name="readSend")
     */
    public function readsend(Message $message): Response
    {
//        $message->setIsRead(true);
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($message);
//        $em->flush();

        return $this->render('message/readsend.html.twig', compact("message"));
    }

    /**
     * @Route("/Replay/{id}", name="reply")
     */
    public function reply(MessageRecipient $message, Request $request, EntityManagerInterface $em): Response
    {
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
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Message $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("received");
    }
}
