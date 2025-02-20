<?php

namespace App\Entity;

use App\Repository\HeureSuplementaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeureSuplementaireRepository::class)
 */
class HeureSuplementaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="heureSuplementaires")
     */
    private $employe;

    /**
     * @ORM\Column(type="float")
     */
    private $duree;

    /**
     * @ORM\Column(type="integer")
     */
    private $tauxHoraire;

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

    public function getDuree(): ?float
    {
        return $this->duree;
    }

    public function setDuree(float $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getTauxHoraire(): ?int
    {
        return $this->tauxHoraire;
    }

    public function setTauxHoraire(int $tauxHoraire): self
    {
        $this->tauxHoraire = $tauxHoraire;

        return $this;
    }
    
}
