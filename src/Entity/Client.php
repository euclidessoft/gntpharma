<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="client")
 */

class Client extends User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $compte;

    public function getCompte(): ?int
    {
        return $this->compte;
    }

    public function setCompte(?int $compte): self
    {
        $this->compte = $compte;

        return $this;
    }
}
