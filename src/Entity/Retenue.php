<?php

namespace App\Entity;

use App\Repository\RetenueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RetenueRepository::class)
 */
class Retenue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Paie::class, inversedBy="retenues")
     */
    private $paie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDeduction;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="retenues")
     */
    private $employe;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getPaie(): ?Paie
    {
        return $this->paie;
    }

    public function setPaie(?Paie $paie): self
    {
        $this->paie = $paie;

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

    public function getDateDeduction(): ?\DateTimeInterface
    {
        return $this->dateDeduction;
    }

    public function setDateDeduction(\DateTimeInterface $dateDeduction): self
    {
        $this->dateDeduction = $dateDeduction;

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
}
