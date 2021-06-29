<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 */
class Appointment
{
    public static $STATUT_PENDIND = 0;
    public static $STATUT_PENDIND_PAYMENT = 1;
    public static $STATUT_COMPLETE = 2;
    public static $STATUT_CANCELLED = 3;

    public static $STATUT_NOT_PAIED = 0;
    public static $STATUT_PAIED = 1;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product_sku;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $product_price;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $product_subtotal;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $product_total;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $isPayed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_paied;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="appointments")
     */
    private $patientID;

    /**
     * @ORM\ManyToOne(targetEntity=Doctor::class, inversedBy="appointments")
     */
    private $doctorID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $orderID;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $app_date;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $app_time;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $create_time;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(?string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductSku(): ?string
    {
        return $this->product_sku;
    }

    public function setProductSku(?string $product_sku): self
    {
        $this->product_sku = $product_sku;

        return $this;
    }

    public function getProductPrice(): ?string
    {
        return $this->product_price;
    }

    public function setProductPrice(?string $product_price): self
    {
        $this->product_price = $product_price;

        return $this;
    }

    public function getProductSubtotal(): ?string
    {
        return $this->product_subtotal;
    }

    public function setProductSubtotal(?string $product_subtotal): self
    {
        $this->product_subtotal = $product_subtotal;

        return $this;
    }

    public function getProductTotal(): ?string
    {
        return $this->product_total;
    }

    public function setProductTotal(?string $product_total): self
    {
        $this->product_total = $product_total;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPatientID(): ?Patient
    {
        return $this->patientID;
    }

    public function setPatientID(?Patient $patientID): self
    {
        $this->patientID = $patientID;

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

    public function getIsPayed(): ?int
    {
        return $this->isPayed;
    }

    public function setIsPayed(?int $isPayed): self
    {
        $this->isPayed = $isPayed;

        return $this;
    }

    public function getDatePaied(): ?DateTimeInterface
    {
        return $this->date_paied;
    }

    public function setDatePaied(?DateTimeInterface $date_paied): self
    {
        $this->date_paied = $date_paied;

        return $this;
    }

    public function getOrderID(): ?string
    {
        return $this->orderID;
    }

    public function setOrderID(?string $orderID): self
    {
        $this->orderID = $orderID;

        return $this;
    }

    public function getAppDate(): ?DateTimeInterface
    {
        return $this->app_date;
    }

    public function setAppDate(?DateTimeInterface $app_date): self
    {
        $this->app_date = $app_date;

        return $this;
    }

    public function getAppTime(): ?DateTimeInterface
    {
        return $this->app_time;
    }

    public function setAppTime(?DateTimeInterface $app_time): self
    {
        $this->app_time = $app_time;

        return $this;
    }

    public function getCreateTime(): ?DateTimeInterface
    {
        return $this->create_time;
    }

    public function setCreateTime(?DateTimeInterface $create_time): self
    {
        $this->create_time = $create_time;

        return $this;
    }

    public function getAppCombinedDate()
    {
        $date = $this->app_date;
        $time = $this->app_time;
        return $date->format("Y-m-d") . " " . $time->format("H:i:s");
    }
}
