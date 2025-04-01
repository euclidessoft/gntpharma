<?php

namespace App\Entity;

use App\Repository\CongesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CongesRepository::class)
 */
class Conges
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="conges")
     */
    private $employe;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="La date de début est obligatoire.")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="La date de fin est obligatoire.")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable="true")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=TypeConge::class, inversedBy="conges")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable="true")
     */
    private $status;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dateModifier= false;

    /**
     * @ORM\Column(type="date", nullable="true")
     */
    private $dateDebutAccorder;

    /**
     * @ORM\Column(type="date", nullable="true")
     */
    private $dateFinAccorder;

    /**
     * @ORM\Column(type="boolean", nullable="true")
     */
    private $confirmer;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->status = null;
        $this->dateModifier = false;
        $this->confirmer = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateModifier(): ?bool
    {
        return $this->dateModifier;
    }

    public function setDateModifier(bool $dateModifier): self
    {
        $this->dateModifier = $dateModifier;

        return $this;
    }

    public function getDateDebutAccorder(): ?\DateTimeInterface
    {
        return $this->dateDebutAccorder;
    }

    public function setDateDebutAccorder(?\DateTimeInterface $dateDebutAccorder): self
    {
        $this->dateDebutAccorder = $dateDebutAccorder;

        return $this;
    }

    public function getDateFinAccorder(): ?\DateTimeInterface
    {
        return $this->dateFinAccorder;
    }

    public function setDateFinAccorder(?\DateTimeInterface $dateFinAccorder): self
    {
        $this->dateFinAccorder = $dateFinAccorder;

        return $this;
    }

    public function getConfirmer(): ?bool
    {
        return $this->confirmer;
    }

    public function setConfirmer(?bool $confirmer): self
    {
        $this->confirmer = $confirmer;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getType(): ?TypeConge
    {
        return $this->type;
    }

    public function setType(?TypeConge $type): self
    {
        $this->type = $type;

        return $this;
    }

}
