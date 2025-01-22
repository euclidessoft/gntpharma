<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormationRepository::class)
 */
class Formation
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
     * @ORM\Column(type="datetime")
     */
    private $datedebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datefin;


    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=EmployeFormation::class, mappedBy="formation")
     */
    private $employeFormations;

    /**
     * @ORM\ManyToMany(targetEntity=Employe::class, inversedBy="formations")
     */
    private $employe;

    public function __construct()
    {
        $this->employeFormations = new ArrayCollection();
        $this->employe = new ArrayCollection();
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

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }


    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

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

    /**
     * @return Collection|EmployeFormation[]
     */
    public function getEmployeFormations(): Collection
    {
        return $this->employeFormations;
    }

    public function addEmployeFormation(EmployeFormation $employeFormation): self
    {
        if (!$this->employeFormations->contains($employeFormation)) {
            $this->employeFormations[] = $employeFormation;
            $employeFormation->setFormation($this);
        }

        return $this;
    }

    public function removeEmployeFormation(EmployeFormation $employeFormation): self
    {
        if ($this->employeFormations->removeElement($employeFormation)) {
            // set the owning side to null (unless already changed)
            if ($employeFormation->getFormation() === $this) {
                $employeFormation->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Employe[]
     */
    public function getEmploye(): Collection
    {
        return $this->employe;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->employe->contains($employe)) {
            $this->employe[] = $employe;
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        $this->employe->removeElement($employe);

        return $this;
    }
}
