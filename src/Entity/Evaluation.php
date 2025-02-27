<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluationRepository::class)
 */
class Evaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEvaluation;

    /**
     * @ORM\Column(type="float")
     */
    private $scoreTotal;

    /**
     * @ORM\Column(type="text")
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity=ResultatEvaluation::class, mappedBy="evaluation")
     */
    private $resultatEvaluations;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="evaluations")
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity=ResultatEvaluation::class, inversedBy="evaluations")
     */
    private $resulatEvaluation;

    public function __construct()
    {
        $this->resultatEvaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEvaluation(): ?\DateTimeInterface
    {
        return $this->dateEvaluation;
    }

    public function setDateEvaluation(\DateTimeInterface $dateEvaluation): self
    {
        $this->dateEvaluation = $dateEvaluation;

        return $this;
    }

    public function getScoreTotal(): ?float
    {
        return $this->scoreTotal;
    }

    public function setScoreTotal(float $scoreTotal): self
    {
        $this->scoreTotal = $scoreTotal;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

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
            $resultatEvaluation->setEvaluation($this);
        }

        return $this;
    }

    public function removeResultatEvaluation(ResultatEvaluation $resultatEvaluation): self
    {
        if ($this->resultatEvaluations->removeElement($resultatEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($resultatEvaluation->getEvaluation() === $this) {
                $resultatEvaluation->setEvaluation(null);
            }
        }

        return $this;
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

    public function getResulatEvaluation(): ?ResultatEvaluation
    {
        return $this->resulatEvaluation;
    }

    public function setResulatEvaluation(?ResultatEvaluation $resulatEvaluation): self
    {
        $this->resulatEvaluation = $resulatEvaluation;

        return $this;
    }
}
