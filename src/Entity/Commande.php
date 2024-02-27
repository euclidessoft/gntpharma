<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Versement", mappedBy="commande")
     */
    private $versements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $livreur;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Paiement")
     * @ORM\JoinColumn(nullable=true)
     */
    private $paiement;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $Montant;

    /**
     * @ORM\Column(type="float")
     */
    private $versement;

    /**
     * @ORM\Column(type="float")
     */
    private $reduction;

    /**
     * @ORM\Column(type="boolean")
     */
    private $suivi;

    /**
     * @ORM\Column(type="boolean")
     */
    private $livraison;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datelivrer;

    /**
     * @ORM\Column(type="float")
     */
    private $tva;

    /**
     * @ORM\Column(type="boolean")
     */
    private $credit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \Datetime();
        $this->ref = 'hhfhfhf';
        $this->suivi = false;
        $this->credit = false;
        $this->livraison = false;
        $this->reduction = 0;
        $this->tva = 0;
        $this->versements = new ArrayCollection();
        $this->versement = 0;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): self
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getSuivi(): ?bool
    {
        return $this->suivi;
    }

    public function setSuivi(bool $suivi): self
    {
        $this->suivi = $suivi;

        return $this;
    }

    public function getLivreur(): ?User
    {
        return $this->livreur;
    }

    public function setLivreur(?User $livreur): self
    {
        $this->livreur = $livreur;

        return $this;
    }

    public function getLivraison(): ?bool
    {
        return $this->livraison;
    }

    public function setLivraison(bool $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getDatelivrer(): ?\DateTimeInterface
    {
        return $this->datelivrer;
    }

    public function setDatelivrer(\DateTimeInterface $datelivrer): self
    {
        $this->datelivrer = $datelivrer;

        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement): self
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getReduction(): ?float
    {
        return $this->reduction;
    }

    public function setReduction(?float $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getCredit(): ?bool
    {
        return $this->credit;
    }

    public function setCredit(bool $credit): self
    {
        $this->credit = $credit;

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
            $versement->setCommande($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): self
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getCommande() === $this) {
                $versement->setCommande(null);
            }
        }

        return $this;
    }

    public function getVersement(): ?float
    {
        return $this->versement;
    }

    public function setVersement(float $versement): self
    {
        $this->versement = $versement;

        return $this;
    }
}
