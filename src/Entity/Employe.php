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

    /**
     * @ORM\OneToMany(targetEntity=EmployeFormation::class, mappedBy="employe")
     */
    private $employeFormations;

    /**
     * @ORM\ManyToMany(targetEntity=Formation::class, mappedBy="employe")
     */
    private $formations;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $famillyname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $famillylink;

    /**
     * @ORM\Column(type="integer")
     */
    private $famillyphone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bloodgroup;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $diabete;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $handicap;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hypo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hyper;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $epilepsie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $remark;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enfant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Conges::class, mappedBy="employe")
     */
    private $conges;

    /**
     * @ORM\OneToMany(targetEntity=CongeAccorder::class, mappedBy="employe")
     */
    private $congeAccorders;

    /**
     * @ORM\OneToMany(targetEntity=Absence::class, mappedBy="employe")
     */
    private $absences;


    public function __construct()
    {
        parent::__construct();
        $this->posteEmployes = new ArrayCollection();
        $this->employeFormations = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->conges = new ArrayCollection();
        $this->congeAccorders = new ArrayCollection();
        $this->absences = new ArrayCollection();
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
            $employeFormation->setEmploye($this);
        }

        return $this;
    }

    public function removeEmployeFormation(EmployeFormation $employeFormation): self
    {
        if ($this->employeFormations->removeElement($employeFormation)) {
            // set the owning side to null (unless already changed)
            if ($employeFormation->getEmploye() === $this) {
                $employeFormation->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Formation[]
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations[] = $formation;
            $formation->addEmploye($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeEmploye($this);
        }

        return $this;
    }

    public function getFamillyname(): ?string
    {
        return $this->famillyname;
    }

    public function setFamillyname(string $famillyname): self
    {
        $this->famillyname = $famillyname;

        return $this;
    }

    public function getFamillylink(): ?string
    {
        return $this->famillylink;
    }

    public function setFamillylink(string $famillylink): self
    {
        $this->famillylink = $famillylink;

        return $this;
    }

    public function getFamillyphone(): ?int
    {
        return $this->famillyphone;
    }

    public function setFamillyphone(int $famillyphone): self
    {
        $this->famillyphone = $famillyphone;

        return $this;
    }

    public function getBloodgroup(): ?string
    {
        return $this->bloodgroup;
    }

    public function setBloodgroup(string $bloodgroup): self
    {
        $this->bloodgroup = $bloodgroup;

        return $this;
    }

    public function getDiabete(): ?string
    {
        return $this->diabete;
    }

    public function setDiabete(string $diabete): self
    {
        $this->diabete = $diabete;

        return $this;
    }

    public function getHandicap(): ?string
    {
        return $this->handicap;
    }

    public function setHandicap(string $handicap): self
    {
        $this->handicap = $handicap;

        return $this;
    }

    public function getHypo(): ?string
    {
        return $this->hypo;
    }

    public function setHypo(string $hypo): self
    {
        $this->hypo = $hypo;

        return $this;
    }

    public function getHyper(): ?string
    {
        return $this->hyper;
    }

    public function setHyper(string $hyper): self
    {
        $this->hyper = $hyper;

        return $this;
    }

    public function getEpilepsie(): ?string
    {
        return $this->epilepsie;
    }

    public function setEpilepsie(string $epilepsie): self
    {
        $this->epilepsie = $epilepsie;

        return $this;
    }

    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getEnfant(): ?int
    {
        return $this->enfant;
    }

    public function setEnfant(int $enfant): self
    {
        $this->enfant = $enfant;

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

    /**
     * @return Collection|Conges[]
     */
    public function getConges(): Collection
    {
        return $this->conges;
    }

    public function addConge(Conges $conge): self
    {
        if (!$this->conges->contains($conge)) {
            $this->conges[] = $conge;
            $conge->setEmploye($this);
        }

        return $this;
    }

    public function removeConge(Conges $conge): self
    {
        if ($this->conges->removeElement($conge)) {
            // set the owning side to null (unless already changed)
            if ($conge->getEmploye() === $this) {
                $conge->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CongeAccorder[]
     */
    public function getCongeAccorders(): Collection
    {
        return $this->congeAccorders;
    }

    public function addCongeAccorder(CongeAccorder $congeAccorder): self
    {
        if (!$this->congeAccorders->contains($congeAccorder)) {
            $this->congeAccorders[] = $congeAccorder;
            $congeAccorder->setEmploye($this);
        }

        return $this;
    }

    public function removeCongeAccorder(CongeAccorder $congeAccorder): self
    {
        if ($this->congeAccorders->removeElement($congeAccorder)) {
            // set the owning side to null (unless already changed)
            if ($congeAccorder->getEmploye() === $this) {
                $congeAccorder->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Absence[]
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absence $absence): self
    {
        if (!$this->absences->contains($absence)) {
            $this->absences[] = $absence;
            $absence->setEmploye($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): self
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getEmploye() === $this) {
                $absence->setEmploye(null);
            }
        }

        return $this;
    }


}


?>