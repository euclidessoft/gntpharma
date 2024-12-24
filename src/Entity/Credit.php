<?php

namespace App\Entity;

use App\Repository\CreditRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CreditRepository::class)
 */
class Credit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Financement::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $financement;

    /**
     * @ORM\ManyToOne(targetEntity=Paiement::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $paiement;

    /**
     * @ORM\ManyToOne(targetEntity=Versement::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $versement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFinancement(): ?Financement
    {
        return $this->financement;
    }

    public function setFinancement(?Financement $financement = null): self
    {
        $this->financement = $financement;

        return $this;
    }

    public function getPaiement(): ?Paiement
    {
        return $this->paiement;
    }

    public function setPaiement(?Paiement $paiement = null): self
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getVersement(): ?Versement
    {
        return $this->versement;
    }

    public function setVersement(?Versement $versement = null): self
    {
        $this->versement = $versement;

        return $this;
    }
}
