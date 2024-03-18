<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $designation;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message = "Champ obligatoire")
     * @Assert\Date(message = "Format invalide")
     */
    private $debut;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message = "Champ obligatoire")
     * @Assert\Date(message = "Format invalide")
     */
    private $fin;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Champ obligatoire")
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $premier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ugpremier;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $deuxieme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ugdeuxieme;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $troisieme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ugtroisieme;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quatrieme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ugquatrieme;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cinquieme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ugcinquieme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reduction;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPremier(): ?int
    {
        return $this->premier;
    }

    public function setPremier(int $premier): self
    {
        $this->premier = $premier;

        return $this;
    }

    public function getUgpremier(): ?int
    {
        return $this->ugpremier;
    }

    public function setUgpremier(int $ugpremier): self
    {
        $this->ugpremier = $ugpremier;

        return $this;
    }

    public function getDeuxieme(): ?int
    {
        return $this->deuxieme;
    }

    public function setDeuxieme(int $deuxieme): self
    {
        $this->deuxieme = $deuxieme;

        return $this;
    }

    public function getUgdeuxieme(): ?int
    {
        return $this->ugdeuxieme;
    }

    public function setUgdeuxieme(int $ugdeuxieme): self
    {
        $this->ugdeuxieme = $ugdeuxieme;

        return $this;
    }

    public function getTroisieme(): ?int
    {
        return $this->troisieme;
    }

    public function setTroisieme(int $troisieme): self
    {
        $this->troisieme = $troisieme;

        return $this;
    }

    public function getUgtroisieme(): ?int
    {
        return $this->ugtroisieme;
    }

    public function setUgtroisieme(int $ugtroisieme): self
    {
        $this->ugtroisieme = $ugtroisieme;

        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(int $reduction): self
    {
        $this->reduction = $reduction;

        return $this;
    }

    public function getQuatrieme(): ?int
    {
        return $this->quatrieme;
    }

    public function setQuatrieme(?int $quatrieme): self
    {
        $this->quatrieme = $quatrieme;

        return $this;
    }

    public function getUgquatrieme(): ?int
    {
        return $this->ugquatrieme;
    }

    public function setUgquatrieme(?int $ugquatrieme): self
    {
        $this->ugquatrieme = $ugquatrieme;

        return $this;
    }

    public function getCinquieme(): ?int
    {
        return $this->cinquieme;
    }

    public function setCinquieme(?int $cinquieme): self
    {
        $this->cinquieme = $cinquieme;

        return $this;
    }

    public function getUgcinquieme(): ?int
    {
        return $this->ugcinquieme;
    }

    public function setUgcinquieme(?int $ugcinquieme): self
    {
        $this->ugcinquieme = $ugcinquieme;

        return $this;
    }
}
