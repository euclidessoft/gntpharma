<?php

namespace App\Entity;

use App\Repository\DebitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DebitRepository::class)
 */
class Debit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Depense::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $depense;

    /**
     * @ORM\Column(type="date")
     */
    private $date;



    /**
     * @ORM\Column(type="integer")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToOne(targetEntity=Transfert::class, cascade={"persist", "remove"})
     */
    private $transfert;

    /**
     * @ORM\Column(type="integer")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=Remboursement::class)
     */
    private $remboursement;

    /**
     * @ORM\ManyToOne(targetEntity=Achat::class)
     */
    private $achat;

    /**
     * @ORM\ManyToOne(targetEntity=PaieSalaire::class)
     */
    private $salaire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \Datetime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepense(): ?Depense
    {
        return $this->depense;
    }

    public function setDepense(?Depense $depense = null): self
    {
        $this->depense = $depense;

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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTransfert(): ?Transfert
    {
        return $this->transfert;
    }

    public function setTransfert(?Transfert $transfert): self
    {
        $this->transfert = $transfert;

        return $this;
    }

    public function getCompte(): ?int
    {
        return $this->compte;
    }

    public function setCompte(int $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getRemboursement(): ?Remboursement
    {
        return $this->remboursement;
    }

    public function setRemboursement(?Remboursement $remboursement): self
    {
        $this->remboursement = $remboursement;

        return $this;
    }

    public function getAchat(): ?Achat
    {
        return $this->achat;
    }

    public function setAchat(?Achat $achat): self
    {
        $this->achat = $achat;

        return $this;
    }

    public function getSalaire(): ?PaieSalaire
    {
        return $this->salaire;
    }

    public function setSalaire(?PaieSalaire $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

}
