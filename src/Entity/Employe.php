<?php


// src/Entity/Employe.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="employe")
 */
class Employe extends User implements UserInterface
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $department;

    /**
     * @ORM\Column(type="date")
     */
    private $hireDate;

    // Getters et setters

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;
        return $this;
    }

    public function getHireDate(): ?\DateTimeInterface
    {
        return $this->hireDate;
    }

    public function setHireDate(\DateTimeInterface $hireDate): self
    {
        $this->hireDate = $hireDate;
        return $this;
    }
}


?>