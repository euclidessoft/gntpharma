<?php

namespace App\Entity;

use App\Repository\MessageRecipientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRecipientRepository::class)
 */
class MessageRecipient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Le message associé.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Message", inversedBy="recipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $message;

    /**
     * Le destinataire du message.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * Le destinataire du message.
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="received")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipient;

    /**
     * Indique si le message a été lu par ce destinataire.
     *
     * @ORM\Column(type="boolean")
     */
    private $isRead = false;

    /**
     * @ORM\OneToMany(targetEntity=MessageReply::class, mappedBy="message")
     */
    private $messageReplies;

    public function __construct()
    {
        $this->messageReplies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Collection|MessageReply[]
     */
    public function getMessageReplies(): Collection
    {
        return $this->messageReplies;
    }

    public function addMessageReply(MessageReply $messageReply): self
    {
        if (!$this->messageReplies->contains($messageReply)) {
            $this->messageReplies[] = $messageReply;
            $messageReply->setMessage($this);
        }

        return $this;
    }

    public function removeMessageReply(MessageReply $messageReply): self
    {
        if ($this->messageReplies->removeElement($messageReply)) {
            // set the owning side to null (unless already changed)
            if ($messageReply->getMessage() === $this) {
                $messageReply->setMessage(null);
            }
        }

        return $this;
    }
}
