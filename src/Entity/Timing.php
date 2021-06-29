<?php

namespace App\Entity;

use App\Repository\TimingRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimingRepository::class)
 */
class Timing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $day;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $month;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $times;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $time_slot;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="idTiming")
     */
    private $idDoctor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $week;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(?int $month): self
    {
        $this->month = $month;

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

    public function getDayNameOfMonth(): ?string
    {
        $dateDB = strval($this->getDay()) . '/' . strval($this->month + 1) . '/' . strval($this->year);
        $d = DateTime::createFromFormat('d/m/Y', $dateDB);
        return $d->format('D');
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(?int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getNameOfMonth(): ?string
    {
        $dateDB = strval($this->getDay()) . '/' . strval($this->month) . '/' . strval($this->year);
        $d = DateTime::createFromFormat('d/m/Y', $dateDB);
        return $d->format('M');
    }

    public function getTimes(): ?string
    {
        return $this->times;
    }

    public function setTimes(?string $times): self
    {
        $this->times = $times;

        return $this;
    }

    public function getTimeSlot(): ?string
    {
        return $this->time_slot;
    }

    public function setTimeSlot(?string $time_slot): self
    {
        $this->time_slot = $time_slot;

        return $this;
    }

    public function getTimesArray(): ?array
    {
        return json_decode($this->times);
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

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(?int $week): self
    {
        $this->week = $week;

        return $this;
    }
}
