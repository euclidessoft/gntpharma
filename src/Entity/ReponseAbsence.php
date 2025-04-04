<?php

namespace App\Entity;

use App\Repository\ReponseAbsenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseAbsenceRepository::class)
 */
class ReponseAbsence
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
    private $cause;

    /**
     * @ORM\ManyToOne(targetEntity=Absence::class, inversedBy="reponseAbsences")
     */
    private $absence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\Column(type="text")
     */
    private $reponse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCause(): ?string
    {
        return $this->cause;
    }

    public function setCause(string $cause): self
    {
        $this->cause = $cause;

        return $this;
    }

    public function getAbsence(): ?Absence
    {
        return $this->absence;
    }

    public function setAbsence(?Absence $absence): self
    {
        $this->absence = $absence;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }
}
