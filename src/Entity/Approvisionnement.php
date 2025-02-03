<?php

namespace App\Entity;

use App\Repository\ApprovisionnementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprovisionnementRepository::class)
 */
class Approvisionnement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Approvisionner")
     * @ORM\JoinColumn(nullable=false)
     */
    private $approvisionner;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $archive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lot;

    /**
     * @ORM\Column(type="date")
     */
    private $peremption;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="approvisionnements")
     */
    private $fournisseur;

    /**
     * Constructor
     */
    public function __construct(Produit $produit, Approvisionner $approvisionner, $quantite, Fournisseur $fournisseur)
    {
        $this->date = new \Datetime();
        $this->produit = $produit;
        $this->archive = $produit->getStock();
        $this->approvisionner = $approvisionner;
        $this->quantite = $quantite;
        $this->fournisseur = $fournisseur;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getApprovisionner(): ?Approvisionner
    {
        return $this->approvisionner;
    }

    public function setApprovisionner(?Approvisionner $approvisionner): self
    {
        $this->approvisionner = $approvisionner;

        return $this;
    }

    public function getArchive(): ?int
    {
        return $this->archive;
    }

    public function setArchive(int $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLot(): ?string
    {
        return $this->lot;
    }

    public function setLot(string $lot): self
    {
        $this->lot = $lot;

        return $this;
    }

    public function getPeremption(): ?\DateTimeInterface
    {
        return $this->peremption;
    }

    public function setPeremption(\DateTimeInterface $peremption): self
    {
        $this->peremption = $peremption;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }
}
