<?php

namespace App\Entity;

use App\Repository\PaieSalaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaieSalaireRepository::class)
 */
class PaieSalaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="paieSalaires")
     */
    private $employe;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $compte;

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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getUser(): ?Employe
    {
        return $this->user;
    }

    public function setUser(?Employe $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCompte(): ?int
    {
        return $this->compte;
    }

    public function setCompte(int $compte): self
    {
        $this->compte = $compte;

        return $this;
    }
}
