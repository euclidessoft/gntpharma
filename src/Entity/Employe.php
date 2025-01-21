<?php


// src/Entity/Employe.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="employe")
 */
class Employe extends User implements UserInterface
{
    /**
     * @ORM\Column(type="date")
     */
    private $hireDate;

    /**
     * @ORM\Column(type="date")
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieuNaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nationalite;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="employes")
     */
    private $departement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\ManyToOne(targetEntity=Poste::class, inversedBy="employes")
     */
    private $poste;

    /**
     * @ORM\OneToMany(targetEntity=PosteEmploye::class, mappedBy="employe")
     */
    private $posteEmployes;


    public function __construct()
    {
        parent::__construct();
        $this->posteEmployes = new ArrayCollection();
    }


    // Getters et setters

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hireDate;
    }

    public function setHireDate(\DateTimeInterface $hireDate): self
    {
        $this->hireDate = $hireDate;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieuNaissance;
    }

    public function setLieuNaissance(string $lieuNaissance): self
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * @return Collection|PosteEmploye[]
     */
    public function getPosteEmployes(): Collection
    {
        return $this->posteEmployes;
    }

    public function addPosteEmploye(PosteEmploye $posteEmploye): self
    {
        if (!$this->posteEmployes->contains($posteEmploye)) {
            $this->posteEmployes[] = $posteEmploye;
            $posteEmploye->setEmploye($this);
        }

        return $this;
    }

    public function removePosteEmploye(PosteEmploye $posteEmploye): self
    {
        if ($this->posteEmployes->removeElement($posteEmploye)) {
            // set the owning side to null (unless already changed)
            if ($posteEmploye->getEmploye() === $this) {
                $posteEmploye->setEmploye(null);
            }
        }

        return $this;
    }


}


?>