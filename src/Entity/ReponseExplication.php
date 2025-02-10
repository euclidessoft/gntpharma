<?php

namespace App\Entity;

use App\Repository\ReponseExplicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseExplicationRepository::class)
 */
class ReponseExplication
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="reponseExplications")
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity=DemandeExplication::class, inversedBy="reponseExplications")
     */
    private $demande;

    /**
     * @ORM\Column(type="text")
     */
    private $reponse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateReponse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

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

    public function getDemande(): ?DemandeExplication
    {
        return $this->demande;
    }

    public function setDemande(?DemandeExplication $demande): self
    {
        $this->demande = $demande;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

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
}
