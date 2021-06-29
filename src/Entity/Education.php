<?php

namespace App\Entity;

use App\Repository\EducationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EducationRepository::class)
 */
class Education
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
    private $degree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $college_institute;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $year_completion;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="educations")
     */
    private $doctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(?string $degree): self
    {
        $this->degree = $degree;

        return $this;
    }

    public function getCollegeInstitute(): ?string
    {
        return $this->college_institute;
    }

    public function setCollegeInstitute(?string $college_institute): self
    {
        $this->college_institute = $college_institute;

        return $this;
    }

    public function getYearCompletion(): ?string
    {
        return $this->year_completion;
    }

    public function setYearCompletion(?string $year_completion): self
    {
        $this->year_completion = $year_completion;

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
