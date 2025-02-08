<?php

namespace App\Entity;

use App\Repository\EmployeDemandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeDemandeRepository::class)
 */
class EmployeDemande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="employeDemandes")
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity=DemandeExplication::class, inversedBy="employeDemandes")
     */
    private $demandeExplication;

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

    public function getDemandeExplication(): ?DemandeExplication
    {
        return $this->demandeExplication;
    }

    public function setDemandeExplication(?DemandeExplication $demandeExplication): self
    {
        $this->demandeExplication = $demandeExplication;

        return $this;
    }
}
