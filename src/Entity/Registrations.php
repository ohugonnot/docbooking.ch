<?php

namespace App\Entity;

use App\Repository\RegistrationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistrationsRepository::class)
 */
class Registrations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registrations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="registrations")
     */
    private $idDoctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrations(): ?string
    {
        return $this->registrations;
    }

    public function setRegistrations(?string $registrations): self
    {
        $this->registrations = $registrations;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getIdDoctor(): ?Doctor
    {
        return $this->idDoctor;
    }

    public function setIdDoctor(?Doctor $idDoctor): self
    {
        $this->idDoctor = $idDoctor;

        return $this;
    }
}
