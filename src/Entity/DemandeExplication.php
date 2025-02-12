<?php

namespace App\Entity;

use App\Repository\DemandeExplicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeExplicationRepository::class)
 */
class DemandeExplication
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
    private $objet;

    /**
     * @ORM\Column(type="text")
     */
    private $details;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateIncident;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Sanction::class, mappedBy="explication")
     */
    private $sanctions;

    /**
     * @ORM\ManyToMany(targetEntity=Employe::class, inversedBy="demandeExplications")
     */
    private $employe;

    /**
     * @ORM\OneToMany(targetEntity=ReponseExplication::class, mappedBy="demande")
     */
    private $reponseExplications;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="demandeExplication")
     */
    private $responsable;


    public function __construct()
    {
        $this->sanctions = new ArrayCollection();
        $this->employe = new ArrayCollection();
        $this->reponseExplications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDateIncident(): ?\DateTimeInterface
    {
        return $this->dateIncident;
    }

    public function setDateIncident(\DateTimeInterface $dateIncident): self
    {
        $this->dateIncident = $dateIncident;

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
            $sanction->setExplication($this);
        }

        return $this;
    }

    public function removeSanction(Sanction $sanction): self
    {
        if ($this->sanctions->removeElement($sanction)) {
            // set the owning side to null (unless already changed)
            if ($sanction->getExplication() === $this) {
                $sanction->setExplication(null);
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
            $reponseExplication->setDemande($this);
        }

        return $this;
    }

    public function removeReponseExplication(ReponseExplication $reponseExplication): self
    {
        if ($this->reponseExplications->removeElement($reponseExplication)) {
            // set the owning side to null (unless already changed)
            if ($reponseExplication->getDemande() === $this) {
                $reponseExplication->setDemande(null);
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


}
