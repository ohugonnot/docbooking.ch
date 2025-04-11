<?php

namespace App\Entity;

use App\Repository\AwardsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AwardsRepository::class)
 */
class Awards
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
    private ?string $awards;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $year;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="awards")
     */
    private ?Doctor $doctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAwards(): ?string
    {
        return $this->awards;
    }

    public function setAwards(?string $awards): self
    {
        $this->awards = $awards;

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
