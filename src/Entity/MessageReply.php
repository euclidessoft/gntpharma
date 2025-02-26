<?php

namespace App\Entity;

use App\Repository\MessageReplyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageReplyRepository::class)
 */
class MessageReply
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="messageReplies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $sentAt;

    /**
     * @ORM\ManyToOne(targetEntity=MessageRecipient::class, inversedBy="messageReplies")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSender(): ?Employe
    {
        return $this->sender;
    }

    public function setSender(?Employe $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getMessage(): ?MessageRecipient
    {
        return $this->message;
    }

    public function setMessage(?MessageRecipient $message): self
    {
        $this->message = $message;

        return $this;
    }
}
