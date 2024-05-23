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
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $registrations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $year;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="registrations")
     */
    private ?Doctor $doctor;

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

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }
}
