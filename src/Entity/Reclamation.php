<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $usercloture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="reclamation")
     */
    private $reponses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "Champ obligatoire")
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
     * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $commentaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $motif;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->status = false;
        $this->creation = new \Datetime();
        $this->reponses = new ArrayCollection();
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

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setReclamation($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReclamation() === $this) {
                $reponse->setReclamation(null);
            }
        }

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

    public function getUsercloture(): ?User
    {
        return $this->usercloture;
    }

    public function setUsercloture(?User $usercloture): self
    {
        $this->usercloture = $usercloture;

        return $this;
    }
}
