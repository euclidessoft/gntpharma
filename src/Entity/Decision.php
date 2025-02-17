<?php

namespace App\Entity;

use App\Repository\DecisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DecisionRepository::class)
 */
class Decision
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Absence::class, inversedBy="decisions")
     */
    private $absences;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDecision;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="decisions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $responsable;

    /**
     * @ORM\ManyToOne(targetEntity=DemandeExplication::class, inversedBy="decisions")
     */
    private $explication;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateConfirm;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $demandes;

    /**
     * @ORM\ManyToOne(targetEntity=TypeSanction::class, inversedBy="decisions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typeSanction;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAbsences(): ?Absence
    {
        return $this->absences;
    }

    public function setAbsences(?Absence $absences): self
    {
        $this->absences = $absences;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDateDecision(): ?\DateTimeInterface
    {
        return $this->dateDecision;
    }

    public function setDateDecision(\DateTimeInterface $dateDecision): self
    {
        $this->dateDecision = $dateDecision;

        return $this;
    }

    public function getResponsable(): ?Employe
    {
        return $this->responsable;
    }

    public function setResponsable(?Employe $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getExplication(): ?DemandeExplication
    {
        return $this->explication;
    }

    public function setExplication(?DemandeExplication $explication): self
    {
        $this->explication = $explication;

        return $this;
    }

    public function getDateConfirm(): ?\DateTimeInterface
    {
        return $this->dateConfirm;
    }

    public function setDateConfirm(?\DateTimeInterface $dateConfirm): self
    {
        $this->dateConfirm = $dateConfirm;

        return $this;
    }

    public function getDemandes(): ?string
    {
        return $this->demandes;
    }

    public function setDemandes(?string $demandes): self
    {
        $this->demandes = $demandes;

        return $this;
    }

    public function getTypeSanction(): ?TypeSanction
    {
        return $this->typeSanction;
    }

    public function setTypeSanction(?TypeSanction $typeSanction): self
    {
        $this->typeSanction = $typeSanction;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
