<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 */
class Experience
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
    private ?string $hospital_name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTime $experience_from;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTime $experience_to;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $designation;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="experiences")
     */
    private ?Doctor $doctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHospitalName(): ?string
    {
        return $this->hospital_name;
    }

    public function setHospitalName(?string $hospital_name): self
    {
        $this->hospital_name = $hospital_name;

        return $this;
    }

    public function getExperienceFrom()
    {
        return $this->experience_from;
    }

    public function setExperienceFrom($experience_from): self
    {
        $this->experience_from = $experience_from;

        return $this;
    }

    public function getExperienceTo()
    {
        return $this->experience_to;
    }

    public function setExperienceTo($experience_to): self
    {
        $this->experience_to = $experience_to;

        return $this;
    }

    public function getExperienceToCustom()
    {
        return ($this->experience_to->format('Y') == date('Y')) ? 'Present' : $this->experience_to->format('Y');
    }

    public function getExperienceTotalYear()
    {
        $value = $this->experience_to->format('Y') - $this->experience_from->format('Y');
        $output = $value . 'year';
        if ($value < 2) {
            $output = $value . 'years';
        }
        $output = '(' . $output . ')';
        return $output;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

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
