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
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;


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
     * @ORM\Column(type="boolean")
     */
    private $reste;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantitelivrer;
    /**
     * @ORM\Column(type="integer")
     */
    private $restealivrer;

    /**
     * @ORM\Column(type="integer")
     */

    private $quantitecommander;

    /**
     * @ORM\Column(type="integer")
     */
    private $archive;

    /**
     * Constructor
     */
    public function __construct(Livrer $livrer, Produit $produit, $quantitecommander, $quantitelivrer, $archive, Commande $commande)
    {
        $this->date = new \Datetime();
        $this->produit = $produit;
        $this->commande = $commande;
        $this->quantitecommander = $quantitecommander;
        $this->archive = $archive;
        $this->quantitelivrer = $quantitelivrer;
        $this->livrer = $livrer;
        $this->reste = false;
        $this->restealivrer = $quantitecommander - $quantitelivrer;
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

    public function getQuantitelivrer(): ?int
    {
        return $this->quantitelivrer;
    }

    public function setQuantitelivrer(int $quantitelivrer): self
    {
        $this->quantitelivrer = $quantitelivrer;

        return $this;
    }

    public function getQuantitecommander(): ?int
    {
        return $this->quantitecommander;
    }

    public function setQuantitecommander(int $quantitecommander): self
    {
        $this->quantitecommander = $quantitecommander;

        return $this;
    }

    public function getReste(): ?bool
    {
        return $this->reste;
    }

    public function setReste(bool $reste): self
    {
        $this->reste = $reste;

        return $this;
    }

    public function getRestealivrer(): ?int
    {
        return $this->restealivrer;
    }

    public function setRestealivrer(int $restealivrer): self
    {
        $this->restealivrer = $restealivrer;

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

}
