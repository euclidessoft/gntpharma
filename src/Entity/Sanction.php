<?php

namespace App\Entity;

use App\Repository\SanctionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SanctionRepository::class)
 */
class Sanction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TypeSanction::class, inversedBy="sanctions")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Absence::class, inversedBy="sanctions")
     */
    private $absence;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateSanction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?TypeSanction
    {
        return $this->type;
    }

    public function setType(?TypeSanction $type): self
    {
        $this->type = $type;

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

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDateSanction(): ?\DateTimeInterface
    {
        return $this->dateSanction;
    }

    public function setDateSanction(\DateTimeInterface $dateSanction): self
    {
        $this->dateSanction = $dateSanction;

        return $this;
    }

}
