<?php

namespace App\Entity;

use App\Repository\EcritureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EcritureRepository::class)
 */
class Ecriture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Debit::class)
     */
    private $debit;

    /**
     * @ORM\ManyToOne(targetEntity=Credit::class)
     */
    private $credit;

    /**
     * @ORM\Column(type="integer")
     */
    private $solde;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comptedebit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comptecredit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $montant;


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

    public function getDebit(): ?Debit
    {
        return $this->debit;
    }

    public function setDebit(?Debit $debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    public function setCredit(?Credit $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $this->solde + $solde;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getComptedebit(): ?string
    {
        return $this->comptedebit;
    }

    public function setComptedebit(string $comptedebit): self
    {
        $this->comptedebit = $comptedebit;

        return $this;
    }

    public function getComptecredit(): ?string
    {
        return $this->comptecredit;
    }

    public function setComptecredit(?string $comptecredit): self
    {
        $this->comptecredit = $comptecredit;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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
}
