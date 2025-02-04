<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Promotion")
     * @ORM\JoinColumn(nullable=true)
     */
    private $promotion;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $desigantion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
      * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
      * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $mincommande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
      * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $fabriquant;


    private $quantite;
    private $lot;
    private $peremption;

    /**
     * @ORM\Column(type="integer")
      * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $prixpublic;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="date")
     */
    private $creation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tva;

    /**
     * @ORM\OneToMany(targetEntity=FournisseurProduit::class, mappedBy="produit")
     */
    private $fournisseurProduits;

    /**
     * @ORM\Column(type="float")
     */
    private $pght;


    public function __construct()
    {
        $this->stock = 0;
        $this->creation = new \Datetime();
        $this->tva = false;
        $this->fournisseurProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDesigantion(): ?string
    {
        return $this->desigantion;
    }

    public function setDesigantion(string $desigantion): self
    {
        $this->desigantion = $desigantion;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getMincommande(): ?string
    {
        return $this->mincommande;
    }

    public function setMincommande(?string $mincommande): self
    {
        $this->mincommande = $mincommande;

        return $this;
    }

    public function getFabriquant(): ?string
    {
        return $this->fabriquant;
    }

    public function setFabriquant(?string $fabriquant): self
    {
        $this->fabriquant = $fabriquant;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
    public function getLot()
    {
        return $this->lot;
    }

    public function setLot($lot)
    {
        $this->lot = $lot;

        return $this;
    }
    public function getPeremption()
    {
        return $this->peremption;
    }

    public function setPeremption($peremption)
    {
        $this->peremption = $peremption;

        return $this;
    }

    public function getPrixpublic(): ?int
    {
        return $this->prixpublic;
    }

    public function setPrixpublic(int $prixpublic): self
    {
        $this->prixpublic = $prixpublic;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function approvisionner(int $quantite): self
    {
        $this->stock = $this->stock + $quantite;

        return $this;
    }

    public function livraison(int $quantite)
    {
        $this->stock = $this->stock - $quantite;
        $res = false;
        if($this->stock >= 0 ){
            $res = true;
        }

        return $res;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getTva(): ?bool
    {
        return $this->tva;
    }

    public function setTva(bool $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection|FournisseurProduit[]
     */
    public function getFournisseurProduits(): Collection
    {
        return $this->fournisseurProduits;
    }

    public function addFournisseurProduit(FournisseurProduit $fournisseurProduit): self
    {
        if (!$this->fournisseurProduits->contains($fournisseurProduit)) {
            $this->fournisseurProduits[] = $fournisseurProduit;
            $fournisseurProduit->setProduit($this);
        }

        return $this;
    }

    public function removeFournisseurProduit(FournisseurProduit $fournisseurProduit): self
    {
        if ($this->fournisseurProduits->removeElement($fournisseurProduit)) {
            // set the owning side to null (unless already changed)
            if ($fournisseurProduit->getProduit() === $this) {
                $fournisseurProduit->setProduit(null);
            }
        }

        return $this;
    }

    public function getPght(): ?float
    {
        return $this->pght;
    }

    public function setPght(float $pght): self
    {
        $this->pght = $pght;

        return $this;
    }
}
