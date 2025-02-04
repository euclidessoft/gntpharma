<?php

namespace App\Entity;

use App\Repository\CongeAccorderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CongeAccorderRepository::class)
 */
class CongeAccorder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebutAccorder;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFinAccorder;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="congeAccorders")
     */
    private $employe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Conges::class, cascade={"persist", "remove"})
     */
    private $conges;

    /**
     * @ORM\OneToOne(targetEntity=TypeConge::class, cascade={"persist", "remove"})
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutAccorder(): ?\DateTimeInterface
    {
        return $this->dateDebutAccorder;
    }

    public function setDateDebutAccorder(\DateTimeInterface $dateDebutAccorder): self
    {
        $this->dateDebutAccorder = $dateDebutAccorder;

        return $this;
    }

    public function getDateFinAccorder(): ?\DateTimeInterface
    {
        return $this->dateFinAccorder;
    }

    public function setDateFinAccorder(\DateTimeInterface $dateFinAccorder): self
    {
        $this->dateFinAccorder = $dateFinAccorder;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getConges(): ?Conges
    {
        return $this->conges;
    }

    public function setConges(?Conges $conges): self
    {
        $this->conges = $conges;

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
