<?php

namespace App\Entity;

use App\Repository\FinancementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FinancementRepository::class)
 */
class Financement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="provenance", type="string", length=255)
     * @Assert\Length(min = 3, minMessage="Longueur comprise Longueur comprise entre 3 et 30 caractères",max = 30, maxMessage="Longueur comprise Longueur comprise entre 3 et 30 caractères")
     * @Assert\NotBlank(message = "Renseignez la provenance")
     * @Assert\Regex(
     * pattern= "/^[0-9a-zA-Z ]+$/",
     * match = true,
     * message = "Vérifiez les caractères saisis")
     */
    private $provenance;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="string", length=255)
     * @Assert\Length(min = 3, minMessage="3 caractères au minimum",max = 100, maxMessage="100 caractères au maximum")
     * @Assert\NotBlank(message = "Renseignez le motif")
     * @Assert\Regex(
     * pattern= "/^[0-9a-zA-Z ]+$/",
     * match = true,
     * message = "Vérifiez les caractères saisis")
     */
    private $motif;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer")
     * @Assert\Length(min = 3, minMessage="3 chiffres au minimum",max = 9, maxMessage="9 chiffres au maximum")
     * @Assert\NotBlank(message = "Renseignez le montant")
     * @Assert\Regex(
     * pattern= "/^\d+$/",
     * match = true,
     * message = "Des Des chiffres uniquement")
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\OneToMany(targetEntity=Remboursement::class, mappedBy="financement")
     */
    private $remboursements;

    /**
     * @ORM\ManyToOne(targetEntity=Banque::class, inversedBy="financements")
     * @ORM\JoinColumn(nullable=true)
     */
    private $banque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $compte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $apport;

    /**
     * @ORM\Column(type="float")
     */
    private $taux;

    /**
     * Set provenance
     *
     * @param string $provenance
     *
     * @return Financement
     */
    public function setProvenance($provenance)
    {
        $this->provenance = $provenance;

        return $this;
    }

    /**
     * Get provenance
     *
     * @return string
     */
    public function getProvenance()
    {
        return $this->provenance;
    }

    /**
     * Set motif
     *
     * @param string $motif
     *
     * @return Financement
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return Financement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \Datetime();
        $this->remboursements = new ArrayCollection();
        $this->apport = true;
        $this->taux = 0;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Gain
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }


    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }


    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * @return Collection|Remboursement[]
     */
    public function getRemboursements(): Collection
    {
        return $this->remboursements;
    }

    public function addRemboursement(Remboursement $remboursement): self
    {
        if (!$this->remboursements->contains($remboursement)) {
            $this->remboursements[] = $remboursement;
            $remboursement->setFinancement($this);
        }

        return $this;
    }

    public function removeRemboursement(Remboursement $remboursement): self
    {
        if ($this->remboursements->removeElement($remboursement)) {
            // set the owning side to null (unless already changed)
            if ($remboursement->getFinancement() === $this) {
                $remboursement->setFinancement(null);
            }
        }

        return $this;
    }

    public function getBanque(): ?Banque
    {
        return $this->banque;
    }

    public function setBanque(?Banque $banque): self
    {
        $this->banque = $banque;

        return $this;
    }

    public function getCompte(): ?string
    {
        return $this->compte;
    }

    public function setCompte(?string $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getApport(): ?bool
    {
        return $this->apport;
    }

    public function setApport(bool $apport): self
    {
        $this->apport = $apport;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }
}
