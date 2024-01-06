<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit")
     * @ORM\JoinColumn(nullable=true)
     */
    private $produits;

    const reclamer = [
        'Produit Livré non facturé' => 'Produit Livré non facturé' ,
        'Produit périmé' => 'Produit périmé' ,
        'Produit cassé' => 'Produit cassé',
        'Péromption proche' => 'Péromption proche',
        'Produit Avarié' => 'Produit Avarié',
        'Quantité facturée erronée' => 'Quantité facturée erronée',
        'produit trop cher' => 'produit trop cher',
        'Retour bon etat' => 'Retour bon etat',
        'Retour bon etat - Doublon commande' => 'Retour bon etat- doublon commande',
        'Retour bon etat - Erruer commande' => 'Retour bon etat- erreur commande',
        'Retour bon etat -  Sursctock' => 'Retour bon etat- Surstock',
        'Produit Facturée différent de produit livré' => 'Produit Facturé différent de produit livré',
        'Produit livré en excédent' => 'Produit livré en excédent',
        'Rappel de lot' => 'Rappel de lot',
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $creation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $cloture;

    /**
     * @ORM\Column(type="text")
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->status = false;
        $this->creation = new \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCloture(): ?\DateTimeInterface
    {
        return $this->cloture;
    }

    public function setCloture(\DateTimeInterface $cloture): self
    {
        $this->cloture = $cloture;

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

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produits->removeElement($produit);

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }
}
