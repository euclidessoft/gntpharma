<?php

namespace App\Entity;

use App\Repository\PromotionProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionProduitRepository::class)
 */
class PromotionProduit
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit")
     */
    private $produit;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="App\Entity\Promotion")
     */
    private $promotion;


    /**
     * Constructor
     */
    public function __construct(Produit $produit, Promotion $promotion)
    {
        $this->produit = $produit;
        $this->promotion = $promotion;
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

    public function getPromotion(): ?Promotion
    {
        return $this->commande;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }
}
