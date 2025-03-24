<?php

namespace App\Entity;

use App\Repository\MoisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoisRepository::class)
 */
class Mois
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Paie::class, mappedBy="mois")
     */
    private $paies;

    public function __construct()
    {
        $this->paies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Paie[]
     */
    public function getPaies(): Collection
    {
        return $this->paies;
    }

    public function addPaie(Paie $paie): self
    {
        if (!$this->paies->contains($paie)) {
            $this->paies[] = $paie;
            $paie->setMois($this);
        }

        return $this;
    }

    public function removePaie(Paie $paie): self
    {
        if ($this->paies->removeElement($paie)) {
            // set the owning side to null (unless already changed)
            if ($paie->getMois() === $this) {
                $paie->setMois(null);
            }
        }

        return $this;
    }
}
