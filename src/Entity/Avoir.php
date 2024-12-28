<?php

namespace App\Entity;

use App\Repository\AvoirRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AvoirRepository::class)
 */
class Avoir
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $admin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reclamation")
     * @ORM\JoinColumn(nullable=true)
     */
    private $reclamation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LivrerReste")
     * @ORM\JoinColumn(nullable=true)
     */
    private $reste;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $Montant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rebourser;

    /**
     * Constructor
     */
    public function __construct(User $client, User $admin, Commande $commande)
    {
        $this->date = new \Datetime();
        $this->client = $client;
        $this->admin = $admin;
        $this->commande = $commande;
        $this->Montant = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): self
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(?User $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

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

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }

    public function getReste(): ?LivrerReste
    {
        return $this->reste;
    }

    public function setReste(?LivrerReste $reste): self
    {
        $this->reste = $reste;

        return $this;
    }

    public function getRebourser(): ?bool
    {
        return $this->rebourser;
    }

    public function setRebourser(bool $rebourser): self
    {
        $this->rebourser = $rebourser;

        return $this;
    }
}
