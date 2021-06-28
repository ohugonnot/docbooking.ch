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
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $websiteURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitterURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagramURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pinterestURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedinURL;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $youtubeURL;

    /**
     * @ORM\OneToMany(targetEntity=Doctor::class, mappedBy="doctorSocial")
     */
    private $DoctorID;

    public function __construct()
    {
        $this->DoctorID = new ArrayCollection();
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
    public function getDoctorID(): Collection
    {
        return $this->DoctorID;
    }

    public function addDoctorID(Doctor $doctorID): self
    {
        if (!$this->DoctorID->contains($doctorID)) {
            $this->DoctorID[] = $doctorID;
            $doctorID->setDoctorSocial($this);
        }

        return $this;
    }

    public function removeDoctorID(Doctor $doctorID): self
    {
        if ($this->DoctorID->contains($doctorID)) {
            $this->DoctorID->removeElement($doctorID);
            // set the owning side to null (unless already changed)
            if ($doctorID->getDoctorSocial() === $this) {
                $doctorID->setDoctorSocial(null);
            }
        }

        return $this;
    }
}
