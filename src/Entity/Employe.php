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

    /**
     * @ORM\ManyToMany(targetEntity=DemandeExplication::class, mappedBy="employe")
     */
    private $demandeExplications;

    /**
     * @ORM\OneToMany(targetEntity=ReponseExplication::class, mappedBy="employe")
     */
    private $reponseExplications;

    /**
     * @ORM\OneToMany(targetEntity=DemandeExplication::class, mappedBy="responsable")
     */
    private $demandeExplication;

    /**
     * @ORM\OneToMany(targetEntity=Absence::class, mappedBy="responsable")
     */
    private $absence;

    /**
     * @ORM\OneToMany(targetEntity=Decision::class, mappedBy="responsable")
     */
    private $decisions;

    /**
     * @ORM\OneToMany(targetEntity=Sanction::class, mappedBy="employe")
     */
    private $sanctions;

    /**
     * @ORM\OneToMany(targetEntity=Paie::class, mappedBy="employe")
     */
    private $paies;

    /**
     * @ORM\OneToMany(targetEntity=HeureSuplementaire::class, mappedBy="employe")
     */
    private $heureSuplementaires;

    /**
     * @ORM\OneToMany(targetEntity=Prime::class, mappedBy="employe")
     */
    private $primes;

    /**
     * @ORM\OneToMany(targetEntity=Retenue::class, mappedBy="employe")
     */
    private $retenues;

    /**
     * @ORM\OneToMany(targetEntity=Calendrier::class, mappedBy="employe")
     */
    private $calendriers;

    /**
     * @ORM\Column(type="integer")
     */
    private $NombreJoursConges;

    /**
     * @ORM\OneToOne(targetEntity=Contrat::class, cascade={"persist", "remove"})
     */
    private $contrat;

    /**
     * @ORM\OneToMany(targetEntity=MessageReply::class, mappedBy="sender")
     */
    private $messageReplies;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="employe")
     */
    private $documents;

    public function __construct()
    {
        parent::__construct();
        $this->posteEmployes = new ArrayCollection();
        $this->employeFormations = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->conges = new ArrayCollection();
        $this->congeAccorders = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->demandeExplications = new ArrayCollection();
        $this->reponseExplications = new ArrayCollection();
        $this->demandeExplication = new ArrayCollection();
        $this->absence = new ArrayCollection();
        $this->decisions = new ArrayCollection();
        $this->sanctions = new ArrayCollection();
        $this->paies = new ArrayCollection();
        $this->heureSuplementaires = new ArrayCollection();
        $this->primes = new ArrayCollection();
        $this->retenues = new ArrayCollection();
        $this->calendriers = new ArrayCollection();
        $this->messageReplies = new ArrayCollection();
        $this->documents = new ArrayCollection();
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

    /**
     * @return Collection|DemandeExplication[]
     */
    public function getDemandeExplications(): Collection
    {
        return $this->demandeExplications;
    }

    public function addDemandeExplication(DemandeExplication $demandeExplication): self
    {
        if (!$this->demandeExplications->contains($demandeExplication)) {
            $this->demandeExplications[] = $demandeExplication;
            $demandeExplication->addEmploye($this);
        }

        return $this;
    }

    public function removeDemandeExplication(DemandeExplication $demandeExplication): self
    {
        if ($this->demandeExplications->removeElement($demandeExplication)) {
            $demandeExplication->removeEmploye($this);
        }

        return $this;
    }

    /**
     * @return Collection|ReponseExplication[]
     */
    public function getReponseExplications(): Collection
    {
        return $this->reponseExplications;
    }

    public function addReponseExplication(ReponseExplication $reponseExplication): self
    {
        if (!$this->reponseExplications->contains($reponseExplication)) {
            $this->reponseExplications[] = $reponseExplication;
            $reponseExplication->setEmploye($this);
        }

        return $this;
    }

    public function removeReponseExplication(ReponseExplication $reponseExplication): self
    {
        if ($this->reponseExplications->removeElement($reponseExplication)) {
            // set the owning side to null (unless already changed)
            if ($reponseExplication->getEmploye() === $this) {
                $reponseExplication->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DemandeExplication[]
     */
    public function getDemandeExplication(): Collection
    {
        return $this->demandeExplication;
    }

    /**
     * @return Collection|Absence[]
     */
    public function getAbsence(): Collection
    {
        return $this->absence;
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
            $decision->setResponsable($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->removeElement($decision)) {
            // set the owning side to null (unless already changed)
            if ($decision->getResponsable() === $this) {
                $decision->setResponsable(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sanction[]
     */
    public function getSanctions(): Collection
    {
        return $this->sanctions;
    }

    public function addSanction(Sanction $sanction): self
    {
        if (!$this->sanctions->contains($sanction)) {
            $this->sanctions[] = $sanction;
            $sanction->setEmploye($this);
        }

        return $this;
    }

    public function removeSanction(Sanction $sanction): self
    {
        if ($this->sanctions->removeElement($sanction)) {
            // set the owning side to null (unless already changed)
            if ($sanction->getEmploye() === $this) {
                $sanction->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Paie[]
     */
    public function getPaies(): Collection
    {
        return $this->paies;
    }

    public function addPaie(Paie $paie): self
    {
        if (!$this->paies->contains($paie)) {
            $this->paies[] = $paie;
            $paie->setEmploye($this);
        }

        return $this;
    }

    public function removePaie(Paie $paie): self
    {
        if ($this->paies->removeElement($paie)) {
            // set the owning side to null (unless already changed)
            if ($paie->getEmploye() === $this) {
                $paie->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HeureSuplementaire[]
     */
    public function getHeureSuplementaires(): Collection
    {
        return $this->heureSuplementaires;
    }

    public function addHeureSuplementaire(HeureSuplementaire $heureSuplementaire): self
    {
        if (!$this->heureSuplementaires->contains($heureSuplementaire)) {
            $this->heureSuplementaires[] = $heureSuplementaire;
            $heureSuplementaire->setEmploye($this);
        }

        return $this;
    }

    public function removeHeureSuplementaire(HeureSuplementaire $heureSuplementaire): self
    {
        if ($this->heureSuplementaires->removeElement($heureSuplementaire)) {
            // set the owning side to null (unless already changed)
            if ($heureSuplementaire->getEmploye() === $this) {
                $heureSuplementaire->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Prime[]
     */
    public function getPrimes(): Collection
    {
        return $this->primes;
    }

    public function addPrime(Prime $prime): self
    {
        if (!$this->primes->contains($prime)) {
            $this->primes[] = $prime;
            $prime->setEmploye($this);
        }

        return $this;
    }

    public function removePrime(Prime $prime): self
    {
        if ($this->primes->removeElement($prime)) {
            // set the owning side to null (unless already changed)
            if ($prime->getEmploye() === $this) {
                $prime->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Retenue[]
     */
    public function getRetenues(): Collection
    {
        return $this->retenues;
    }

    public function addRetenue(Retenue $retenue): self
    {
        if (!$this->retenues->contains($retenue)) {
            $this->retenues[] = $retenue;
            $retenue->setEmploye($this);
        }

        return $this;
    }

    public function removeRetenue(Retenue $retenue): self
    {
        if ($this->retenues->removeElement($retenue)) {
            // set the owning side to null (unless already changed)
            if ($retenue->getEmploye() === $this) {
                $retenue->setEmploye(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Calendrier[]
     */
    public function getCalendriers(): Collection
    {
        return $this->calendriers;
    }

    public function addCalendrier(Calendrier $calendrier): self
    {
        if (!$this->calendriers->contains($calendrier)) {
            $this->calendriers[] = $calendrier;
            $calendrier->setEmploye($this);
        }

        return $this;
    }

    public function removeCalendrier(Calendrier $calendrier): self
    {
        if ($this->calendriers->removeElement($calendrier)) {
            // set the owning side to null (unless already changed)
            if ($calendrier->getEmploye() === $this) {
                $calendrier->setEmploye(null);
            }
        }

        return $this;
    }

    public function getNombreJoursConges(): ?int
    {
        return $this->NombreJoursConges;
    }

    public function setNombreJoursConges(int $NombreJoursConges): self
    {
        $this->NombreJoursConges = $NombreJoursConges;

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * @return Collection|MessageReply[]
     */
    public function getMessageReplies(): Collection
    {
        return $this->messageReplies;
    }

    public function addMessageReply(MessageReply $messageReply): self
    {
        if (!$this->messageReplies->contains($messageReply)) {
            $this->messageReplies[] = $messageReply;
            $messageReply->setSender($this);
        }
        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setEmploye($this);
        }

        return $this;
    }


    public function removeMessageReply(MessageReply $messageReply): self
    {
        if ($this->messageReplies->removeElement($messageReply)) {
            // set the owning side to null (unless already changed)
            if ($messageReply->getSender() === $this) {
                $messageReply->setSender(null);
            }
        }
        return $this;
    }
    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getEmploye() === $this) {
                $document->setEmploye(null);
            }
        }

        return $this;
    }

}


?>