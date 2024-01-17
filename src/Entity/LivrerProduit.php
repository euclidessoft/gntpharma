<?php

namespace App\Entity;

use App\Repository\LivrerProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivrerProduitRepository::class)
 */
class LivrerProduit
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit")
     */
    private $produit;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Livrer")
     */
    private $livrer;

//    /**
//     * @ORM\Id
//     * @ORM\ManyToOne(targetEntity="App\Entity\Commande")
//     */
//    private $commande;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;
    /**
     * @ORM\Column(type="integer")
     */
    private $archive;

    /**
     * Constructor
     */
    public function __construct(Livrer $livrer, Produit $produit, $quantite, $archive)
    {
        $this->date = new \Datetime();
        $this->produit = $produit;
//        $this->commande = $commande;
        $this->archive = $archive;
        $this->quantite = $quantite;
        $this->livrer = $livrer;
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

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

//    public function getCommande(): ?Commande
//    {
//        return $this->commande;
//    }
//
//    public function setCommande(?Commande $commande): self
//    {
//        $this->commande = $commande;
//
//        return $this;
//    }

    public function getLivrer(): ?Livrer
    {
        return $this->livrer;
    }

    public function setLivrer(?Livrer $livrer): self
    {
        $this->livrer = $livrer;

        return $this;
    }

}
