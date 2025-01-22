<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PosteRepository::class)
 */
class Poste
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
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Employe::class, mappedBy="poste")
     */
    private $employes;

    /**
     * @ORM\OneToMany(targetEntity=PosteEmploye::class, mappedBy="poste")
     */
    private $posteEmployes;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="postes")
     */
    private $departement;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
        $this->posteEmployes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Employe[]
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->employes->contains($employe)) {
            $this->employes[] = $employe;
            $employe->setPoste($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getPoste() === $this) {
                $employe->setPoste(null);
            }
        }

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
            $posteEmploye->setPoste($this);
        }

        return $this;
    }

    public function removePosteEmploye(PosteEmploye $posteEmploye): self
    {
        if ($this->posteEmployes->removeElement($posteEmploye)) {
            // set the owning side to null (unless already changed)
            if ($posteEmploye->getPoste() === $this) {
                $posteEmploye->setPoste(null);
            }
        }

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
}
