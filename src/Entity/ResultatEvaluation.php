<?php

namespace App\Entity;

use App\Repository\ResultatEvaluationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResultatEvaluationRepository::class)
 */
class ResultatEvaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CritereEvaluation::class, inversedBy="resultatEvaluations")
     */
    private $critere;

    /**
     * @ORM\Column(type="float")
     */
    private $scoreCritere;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaireCritere;

    /**
     * @ORM\OneToMany(targetEntity=Evaluation::class, mappedBy="resulatEvaluation")
     */
    private $evaluations;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCritere(): ?CritereEvaluation
    {
        return $this->critere;
    }

    public function setCritere(?CritereEvaluation $critere): self
    {
        $this->critere = $critere;

        return $this;
    }

    public function getScoreCritere(): ?float
    {
        return $this->scoreCritere;
    }

    public function setScoreCritere(float $scoreCritere): self
    {
        $this->scoreCritere = $scoreCritere;

        return $this;
    }

    public function getCommentaireCritere(): ?string
    {
        return $this->commentaireCritere;
    }

    public function setCommentaireCritere(?string $commentaireCritere): self
    {
        $this->commentaireCritere = $commentaireCritere;

        return $this;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setResulatEvaluation($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getResulatEvaluation() === $this) {
                $evaluation->setResulatEvaluation(null);
            }
        }

        return $this;
    }
}
