<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
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

    /**
     * @ORM\Column(type="integer")
      * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $prixpublic;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;


    public function __construct()
    {
        $this->stock = 0;
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
}
