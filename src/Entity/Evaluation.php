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
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="evaluations")
     */
    private $employe;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEvaluation;

    /**
     * @ORM\ManyToOne(targetEntity=CritereEvaluation::class, inversedBy="evaluations")
     */
    private $critereEvaluation;

    /**
     * @ORM\OneToMany(targetEntity=EvaluationDetail::class, mappedBy="evaluation")
     */
    private $evaluationDetails;

    public function __construct()
    {
        $this->evaluationDetails = new ArrayCollection();
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

    public function getDateEvaluation(): ?\DateTimeInterface
    {
        return $this->dateEvaluation;
    }

    public function setDateEvaluation(\DateTimeInterface $dateEvaluation): self
    {
        $this->dateEvaluation = $dateEvaluation;

        return $this;
    }

    public function getCritereEvaluation(): ?CritereEvaluation
    {
        return $this->critereEvaluation;
    }

    public function setCritereEvaluation(?CritereEvaluation $critereEvaluation): self
    {
        $this->critereEvaluation = $critereEvaluation;

        return $this;
    }

    /**
     * @return Collection|EvaluationDetail[]
     */
    public function getEvaluationDetails(): Collection
    {
        return $this->evaluationDetails;
    }

    public function addEvaluationDetail(EvaluationDetail $evaluationDetail): self
    {
        if (!$this->evaluationDetails->contains($evaluationDetail)) {
            $this->evaluationDetails[] = $evaluationDetail;
            $evaluationDetail->setEvaluation($this);
        }

        return $this;
    }

    public function removeEvaluationDetail(EvaluationDetail $evaluationDetail): self
    {
        if ($this->evaluationDetails->removeElement($evaluationDetail)) {
            // set the owning side to null (unless already changed)
            if ($evaluationDetail->getEvaluation() === $this) {
                $evaluationDetail->setEvaluation(null);
            }
        }

        return $this;
    }

}
