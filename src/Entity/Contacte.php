<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\EquipRepository;
use Doctrine\ORM\Mapping as ORM;

class Contacte
{


    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    private ?string $correu = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    private ?string $assumpte = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 250)]
    private ?string $missatge = null;


    public function getCorreu(): ?int
    {
        return $this->correu;
    }

    public function getAssumpte(): ?string
    {
        return $this->assumpte;
    }

    public function getMissatge(): ?string
    {
        return $this->missatge;
    }

    public function setMissatge(string $missatge): self
    {
        $this->missatge = $missatge;

        return $this;
    }
    public function setAssumpte(string $assumpte): self
    {
        $this->assumpte = $assumpte;

        return $this;
    }
    public function setCorreu(string $correu): self
    {
        $this->correu = $correu;

        return $this;
    }

}
