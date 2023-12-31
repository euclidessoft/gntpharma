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
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit")
     */
    private $produit;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Approvisionner")
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
     * Constructor
     */
    public function __construct(Produit $produit, Approvisionner $approvisionner, $quantite)
    {
        $this->date = new \Datetime();
        $this->produit = $produit;
        $this->archive = $produit->getStock();
        $this->approvisionner = $approvisionner;
        $this->quantite = $quantite;
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
}
