<?php

namespace App\Entity;

use App\Repository\PaieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaieRepository::class)
 */
class Paie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="paies")
     */
    private $employe;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $salaireBase;

    /**
     * @ORM\OneToMany(targetEntity=Retenue::class, mappedBy="paie")
     */
    private $retenues;

    /**
     * @ORM\Column(type="integer")
     */
    private $salaireNet;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPrime;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalheureSup;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalRetenue;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detailsRetenues;

    /**
     * @ORM\Column(type="float")
     */
    private $impot;

    /**
     * @ORM\ManyToOne(targetEntity=Mois::class, inversedBy="paies")
     */
    private $mois;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payer;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datepaye;

    public function __construct()
    {
        $this->retenues = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

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

    public function getSalaireBase(): ?int
    {
        return $this->salaireBase;
    }

    public function setSalaireBase(int $salaireBase): self
    {
        $this->salaireBase = $salaireBase;

        return $this;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|Retenue[]
     */
    public function getRetenues(): Collection
    {
        return $this->retenues;
    }

    public function addRetenue(Retenue $retenue): self
    {
        if (!$this->retenues->contains($retenue)) {
            $this->retenues[] = $retenue;
            $retenue->setPaie($this);
        }

        return $this;
    }

    public function removeRetenue(Retenue $retenue): self
    {
        if ($this->retenues->removeElement($retenue)) {
            // set the owning side to null (unless already changed)
            if ($retenue->getPaie() === $this) {
                $retenue->setPaie(null);
            }
        }

        return $this;
    }

    public function getSalaireNet(): ?int
    {
        return $this->salaireNet;
    }

    public function setSalaireNet(int $salaireNet): self
    {
        $this->salaireNet = $salaireNet;

        return $this;
    }

    public function getTotalPrime(): ?int
    {
        return $this->totalPrime;
    }

    public function setTotalPrime(int $totalPrime): self
    {
        $this->totalPrime = $totalPrime;

        return $this;
    }

    public function getTotalheureSup(): ?int
    {
        return $this->totalheureSup;
    }

    public function setTotalheureSup(int $totalheureSup): self
    {
        $this->totalheureSup = $totalheureSup;

        return $this;
    }

    public function getTotalRetenue(): ?int
    {
        return $this->totalRetenue;
    }

    public function setTotalRetenue(int $totalRetenue): self
    {
        $this->totalRetenue = $totalRetenue;

        return $this;
    }

    public function getDetailsRetenues(): ?string
    {
        return $this->detailsRetenues;
    }

    public function setDetailsRetenues(?string $detailsRetenues): self
    {
        $this->detailsRetenues = $detailsRetenues;

        return $this;
    }

    public function getImpot(): ?float
    {
        return $this->impot;
    }

    public function setImpot(float $impot): self
    {
        $this->impot = $impot;

        return $this;
    }

    public function getMois(): ?Mois
    {
        return $this->mois;
    }

    public function setMois(?Mois $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getPayer(): ?bool
    {
        return $this->payer;
    }

    public function setPayer(bool $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getDatepaye(): ?\DateTimeInterface
    {
        return $this->datepaye;
    }

    public function setDatepaye(\DateTimeInterface $datepaye): self
    {
        $this->datepaye = $datepaye;

        return $this;
    }
}
