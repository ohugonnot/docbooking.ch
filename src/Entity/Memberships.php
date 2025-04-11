<?php

namespace App\Entity;

use App\Repository\MembershipsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MembershipsRepository::class)
 */
class Memberships
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
    private ?string $memberships;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="memberships")
     */
    private ?Doctor $doctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMemberships(): ?string
    {
        return $this->memberships;
    }

    public function setMemberships(?string $memberships): self
    {
        $this->memberships = $memberships;

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
