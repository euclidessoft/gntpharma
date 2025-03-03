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
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity=Evaluation::class, mappedBy="critereEvaluation")
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity=EvaluationDetail::class, mappedBy="critereEvaluation")
     */
    private $evaluationDetails;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
        $this->evaluationDetails = new ArrayCollection();
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

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

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
            $evaluation->setCritereEvaluation($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getCritereEvaluation() === $this) {
                $evaluation->setCritereEvaluation(null);
            }
        }

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
            $evaluationDetail->setCritereEvaluation($this);
        }

        return $this;
    }

    public function removeEvaluationDetail(EvaluationDetail $evaluationDetail): self
    {
        if ($this->evaluationDetails->removeElement($evaluationDetail)) {
            // set the owning side to null (unless already changed)
            if ($evaluationDetail->getCritereEvaluation() === $this) {
                $evaluationDetail->setCritereEvaluation(null);
            }
        }

        return $this;
    }
    
}
