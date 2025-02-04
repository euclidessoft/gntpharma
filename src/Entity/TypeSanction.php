<?php

namespace App\Entity;

use App\Repository\TypeSanctionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeSanctionRepository::class)
 */
class TypeSanction
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
     * @ORM\OneToMany(targetEntity=Sanction::class, mappedBy="type")
     */
    private $sanctions;

    public function __construct()
    {
        $this->sanctions = new ArrayCollection();
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
     * @return Collection|Sanction[]
     */
    public function getSanctions(): Collection
    {
        return $this->sanctions;
    }

    public function addSanction(Sanction $sanction): self
    {
        if (!$this->sanctions->contains($sanction)) {
            $this->sanctions[] = $sanction;
            $sanction->setType($this);
        }

        return $this;
    }

    public function removeSanction(Sanction $sanction): self
    {
        if ($this->sanctions->removeElement($sanction)) {
            // set the owning side to null (unless already changed)
            if ($sanction->getType() === $this) {
                $sanction->setType(null);
            }
        }

        return $this;
    }
}
