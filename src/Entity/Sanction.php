<?php

namespace App\Entity;

use App\Repository\SanctionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SanctionRepository::class)
 */
class Sanction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TypeSanction::class, inversedBy="sanctions")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Absence::class, inversedBy="sanctions")
     */
    private $absence;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSanction;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="sanctions")
     */
    private $responsable;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $demandes;

    /**
     * @ORM\ManyToOne(targetEntity=DemandeExplication::class, inversedBy="sanctions")
     */
    private $explication;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateConfirm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeSanction
    {
        return $this->type;
    }

    public function setType(?TypeSanction $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAbsence(): ?Absence
    {
        return $this->absence;
    }

    public function setAbsence(?Absence $absence): self
    {
        $this->absence = $absence;

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

    public function getDateSanction(): ?\DateTimeInterface
    {
        return $this->dateSanction;
    }

    public function setDateSanction(\DateTimeInterface $dateSanction): self
    {
        $this->dateSanction = $dateSanction;

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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): self
    {
        $this->montant = $montant;

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

}
