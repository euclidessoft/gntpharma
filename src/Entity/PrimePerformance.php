<?php

namespace App\Entity;

use App\Repository\PrimePerformanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrimePerformanceRepository::class)
 */
class PrimePerformance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="primePerformances")
     */
    private $employe;

    /**
     * @ORM\Column(type="date")
     */
    private $createAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    public function __construct()
    {
        $this->createAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

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
}
