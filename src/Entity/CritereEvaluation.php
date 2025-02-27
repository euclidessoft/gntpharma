<?php

namespace App\Entity;

use App\Repository\CritereEvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CritereEvaluationRepository::class)
 */
class CritereEvaluation
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=ResultatEvaluation::class, mappedBy="critere")
     */
    private $resultatEvaluations;

    public function __construct()
    {
        $this->resultatEvaluations = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ResultatEvaluation[]
     */
    public function getResultatEvaluations(): Collection
    {
        return $this->resultatEvaluations;
    }

    public function addResultatEvaluation(ResultatEvaluation $resultatEvaluation): self
    {
        if (!$this->resultatEvaluations->contains($resultatEvaluation)) {
            $this->resultatEvaluations[] = $resultatEvaluation;
            $resultatEvaluation->setCritere($this);
        }

        return $this;
    }

    public function removeResultatEvaluation(ResultatEvaluation $resultatEvaluation): self
    {
        if ($this->resultatEvaluations->removeElement($resultatEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($resultatEvaluation->getCritere() === $this) {
                $resultatEvaluation->setCritere(null);
            }
        }

        return $this;
    }
}
