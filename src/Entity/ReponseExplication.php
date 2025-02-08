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
     * @ORM\ManyToOne(targetEntity=DemandeExplication::class, inversedBy="reponseExplications")
     */
    private $demandeExplication;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="reponseExplications")
     */
    private $employe;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reponse;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateReponse;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(?\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

}
