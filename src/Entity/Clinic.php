<?php

namespace App\Entity;

use App\Repository\ClinicRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClinicRepository::class)
 */
class Clinic
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="idClinic")
     */
    private $doctorID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }
	
	public function getImagesArray()
    {
        return explode(',', $this->images);
    }

    public function setImages(?string $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getDoctorID(): ?Doctor
    {
        return $this->doctorID;
    }

    public function setDoctorID(?Doctor $doctorID): self
    {
        $this->doctorID = $doctorID;

        return $this;
    }
}
