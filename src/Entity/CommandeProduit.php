<?php

namespace App\Entity;

use App\Repository\CommandeProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeProduitRepository::class)
 */
class CommandeProduit
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit")
     */
     private $produit;

     /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande")
     */
     private $commande;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $session;

    /**
     * @ORM\Column(type="float")
     */
    private $publique;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;


    private $stock;

    /**
     * Constructor
     */
    public function __construct(Produit $produit, Commande $commande, $session, $publique, $quantite)
    {
        $this->date = new \Datetime();
        $this->produit = $produit;
        $this->commande = $commande;
        $this->session = $session;
        $this->publique = $publique;
        $this->quantite = $quantite;
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

    public function getSession(): ?float
    {
        return $this->session;
    }

    public function setSession(float $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getPublique(): ?float
    {
        return $this->publique;
    }

    public function setPublique(float $publique): self
    {
        $this->publique = $publique;

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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
