<?php

namespace App\Entity;

use App\Repository\AbsenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AbsenceRepository::class)
 */
class Absence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="absences")
     */
    private $employe;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAbsence;

    /**
     * @ORM\Column(type="boolean")
     */
    private $justifier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=ReponseAbsence::class, mappedBy="absence")
     */
    private $reponseAbsences;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="absence")
     */
    private $responsable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateConfirm;

    /**
     * @ORM\OneToMany(targetEntity=Decision::class, mappedBy="absences")
     */
    private $decisions;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    public function __construct()
    {
        $this->reponseAbsences = new ArrayCollection();
        $this->decisions = new ArrayCollection();
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

    public function getDateAbsence(): ?\DateTimeInterface
    {
        return $this->dateAbsence;
    }

    public function setDateAbsence(\DateTimeInterface $dateAbsence): self
    {
        $this->dateAbsence = $dateAbsence;

        return $this;
    }

    public function getJustifier(): ?bool
    {
        return $this->justifier;
    }

    public function setJustifier(bool $justifier): self
    {
        $this->justifier = $justifier;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|ReponseAbsence[]
     */
    public function getReponseAbsences(): Collection
    {
        return $this->reponseAbsences;
    }

    public function addReponseAbsence(ReponseAbsence $reponseAbsence): self
    {
        if (!$this->reponseAbsences->contains($reponseAbsence)) {
            $this->reponseAbsences[] = $reponseAbsence;
            $reponseAbsence->setAbsence($this);
        }

        return $this;
    }

    public function removeReponseAbsence(ReponseAbsence $reponseAbsence): self
    {
        if ($this->reponseAbsences->removeElement($reponseAbsence)) {
            // set the owning side to null (unless already changed)
            if ($reponseAbsence->getAbsence() === $this) {
                $reponseAbsence->setAbsence(null);
            }
        }

        return $this;
    }

    public function getResponsable(): ?Employe
    {
        return $this->responsable;
    }

    public function setResponsable(?Employe $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getDateConfirm(): ?\DateTimeInterface
    {
        return $this->dateConfirm;
    }

    public function setDateConfirm(?\DateTimeInterface $dateConfirm): self
    {
        $this->dateConfirm = $dateConfirm;

        return $this;
    }

    /**
     * @return Collection|Decision[]
     */
    public function getDecisions(): Collection
    {
        return $this->decisions;
    }

    public function addDecision(Decision $decision): self
    {
        if (!$this->decisions->contains($decision)) {
            $this->decisions[] = $decision;
            $decision->setAbsences($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->removeElement($decision)) {
            // set the owning side to null (unless already changed)
            if ($decision->getAbsences() === $this) {
                $decision->setAbsences(null);
            }
        }

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

}
