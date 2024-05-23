<?php

namespace App\Entity;

use App\Repository\DoctorSocialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DoctorSocialRepository::class)
 */
class DoctorSocial
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
    private ?string $websiteURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $facebookURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $twitterURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $instagramURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $pinterestURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $linkedinURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $youtubeURL;

    /**
     * @ORM\OneToMany(targetEntity=Doctor::class, mappedBy="doctorSocial", cascade={"persist", "remove"})
     */
    private Collection $doctors;

    public function __construct()
    {
        $this->doctors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebsiteURL(): ?string
    {
        return $this->websiteURL;
    }

    public function setWebsiteURL(?string $websiteURL): self
    {
        $this->websiteURL = $websiteURL;

        return $this;
    }

    public function getFacebookURL(): ?string
    {
        return $this->facebookURL;
    }

    public function setFacebookURL(?string $facebookURL): self
    {
        $this->facebookURL = $facebookURL;

        return $this;
    }

    public function getTwitterURL(): ?string
    {
        return $this->twitterURL;
    }

    public function setTwitterURL(?string $twitterURL): self
    {
        $this->twitterURL = $twitterURL;

        return $this;
    }

    public function getInstagramURL(): ?string
    {
        return $this->instagramURL;
    }

    public function setInstagramURL(?string $instagramURL): self
    {
        $this->instagramURL = $instagramURL;

        return $this;
    }

    public function getPinterestURL(): ?string
    {
        return $this->pinterestURL;
    }

    public function setPinterestURL(?string $pinterestURL): self
    {
        $this->pinterestURL = $pinterestURL;

        return $this;
    }

    public function getLinkedinURL(): ?string
    {
        return $this->linkedinURL;
    }

    public function setLinkedinURL(?string $linkedinURL): self
    {
        $this->linkedinURL = $linkedinURL;

        return $this;
    }

    public function getYoutubeURL(): ?string
    {
        return $this->youtubeURL;
    }

    public function setYoutubeURL(?string $youtubeURL): self
    {
        $this->youtubeURL = $youtubeURL;

        return $this;
    }

    /**
     * @return Collection|Doctor[]
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Doctor $doctor): self
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors[] = $doctor;
            $doctor->setDoctorSocial($this);
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): self
    {
        if ($this->doctors->contains($doctor)) {
            $this->doctors->removeElement($doctor);
            // set the owning side to null (unless already changed)
            if ($doctor->getDoctorSocial() === $this) {
                $doctor->setDoctorSocial(null);
            }
        }

        return $this;
    }
}
