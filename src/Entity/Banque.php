<?php

namespace App\Entity;

use App\Repository\BanqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=BanqueRepository::class)
 * @UniqueEntity(
 *     fields={"compte"},
 *     message="Ce compte existe déjà."
 * )
 */
class Banque
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Renseignez le numéro")
     * @Assert\Regex(
     * pattern= "/[1-9]/",
     * match = true,
     * message = "Numéro incorrecte")
     */
    private $compte;

    /**
     * @ORM\OneToMany(targetEntity=Achat::class, mappedBy="banque")
     */
    private $achats;

    /**
     * @ORM\OneToMany(targetEntity=Transfert::class, mappedBy="banque")
     */
    private $transferts;

    /**
     * @ORM\OneToMany(targetEntity=Remboursement::class, mappedBy="banque")
     */
    private $remboursements;

    /**
     * @ORM\OneToMany(targetEntity=Financement::class, mappedBy="banque")
     */
    private $financements;

    /**
     * @ORM\OneToMany(targetEntity=Depense::class, mappedBy="banque")
     */
    private $depenses;

    /**
     * @ORM\OneToMany(targetEntity=Paiement::class, mappedBy="banque")
     */
    private $paiements;

    /**
     * @ORM\OneToMany(targetEntity=Versement::class, mappedBy="banque")
     */
    private $versements;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
        $this->transferts = new ArrayCollection();
        $this->remboursements = new ArrayCollection();
        $this->financements = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->paiements = new ArrayCollection();
        $this->versements = new ArrayCollection();
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

    public function getCompte(): ?string
    {
        return $this->compte;
    }

    public function setCompte(string $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setBanque($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->removeElement($achat)) {
            // set the owning side to null (unless already changed)
            if ($achat->getBanque() === $this) {
                $achat->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transfert[]
     */
    public function getTransferts(): Collection
    {
        return $this->transferts;
    }

    public function addTransfert(Transfert $transfert): self
    {
        if (!$this->transferts->contains($transfert)) {
            $this->transferts[] = $transfert;
            $transfert->setBanque($this);
        }

        return $this;
    }

    public function removeTransfert(Transfert $transfert): self
    {
        if ($this->transferts->removeElement($transfert)) {
            // set the owning side to null (unless already changed)
            if ($transfert->getBanque() === $this) {
                $transfert->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Remboursement[]
     */
    public function getRemboursements(): Collection
    {
        return $this->remboursements;
    }

    public function addRemboursement(Remboursement $remboursement): self
    {
        if (!$this->remboursements->contains($remboursement)) {
            $this->remboursements[] = $remboursement;
            $remboursement->setBanque($this);
        }

        return $this;
    }

    public function removeRemboursement(Remboursement $remboursement): self
    {
        if ($this->remboursements->removeElement($remboursement)) {
            // set the owning side to null (unless already changed)
            if ($remboursement->getBanque() === $this) {
                $remboursement->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Financement[]
     */
    public function getFinancements(): Collection
    {
        return $this->financements;
    }

    public function addFinancement(Financement $financement): self
    {
        if (!$this->financements->contains($financement)) {
            $this->financements[] = $financement;
            $financement->setBanque($this);
        }

        return $this;
    }

    public function removeFinancement(Financement $financement): self
    {
        if ($this->financements->removeElement($financement)) {
            // set the owning side to null (unless already changed)
            if ($financement->getBanque() === $this) {
                $financement->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Depense[]
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): self
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses[] = $depense;
            $depense->setBanque($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): self
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getBanque() === $this) {
                $depense->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Paiement[]
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements[] = $paiement;
            $paiement->setBanque($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getBanque() === $this) {
                $paiement->setBanque(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Versement[]
     */
    public function getVersements(): Collection
    {
        return $this->versements;
    }

    public function addVersement(Versement $versement): self
    {
        if (!$this->versements->contains($versement)) {
            $this->versements[] = $versement;
            $versement->setBanque($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): self
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getBanque() === $this) {
                $versement->setBanque(null);
            }
        }

        return $this;
    }
}
