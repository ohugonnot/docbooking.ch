<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=DoctorRepository::class)
 */
class Doctor implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gender;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private string $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone_number;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTime $date_birth;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $about_me;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address_line_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address_line_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $state;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postal_code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $picture_profile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $receiving_patient_info;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $price_type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $price_custom_value;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $speciality;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $services;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $specialization;

    /**
     * @ORM\Column(type="simple_array", nullable=false)
     */
    private array $roles;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $create_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $updated_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $business_hours;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=16, nullable=true)
     */
    private ?string $latitude;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=16, nullable=true)
     */
    private ?string $longitude;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private ?string $slug;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private ?array $spoken_languages = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $lang_other;

    /**
     * @ORM\ManyToOne(targetEntity=DoctorSocial::class, inversedBy="doctors")
     */
    private ?DoctorSocial $doctorSocial;

    /**
     * @ORM\OneToMany(targetEntity=Timing::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private Collection $timings;

    /**
     * @ORM\OneToMany(targetEntity=Clinic::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private Collection $clinics;

    /**
     * @ORM\OneToMany(targetEntity=Education::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private Collection $educations;

    /**
     * @ORM\OneToMany(targetEntity=Experience::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private Collection $experiences;

    /**
     * @ORM\OneToMany(targetEntity=Awards::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private Collection $awards;

    /**
     * @ORM\OneToMany(targetEntity=Memberships::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private Collection $memberships;

    /**
     * @ORM\OneToMany(targetEntity=Registrations::class, mappedBy="doctor", cascade={"persist", "remove"})
     */
    private Collection $registrations;

    /**
     * @ORM\OneToMany(targetEntity=Appointment::class, mappedBy="doctor", cascade={"persist", "remove"} )
     */
    private Collection $appointments;

    public function __construct()
    {
        $this->timings = new ArrayCollection();
        $this->clinics = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->awards = new ArrayCollection();
        $this->memberships = new ArrayCollection();
        $this->registrations = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function __toString() : string
    {
        $format = "Doctor (id: %s)\n";
        return sprintf($format, $this->id);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getDateBirth() : ?DateTime
    {
        return $this->date_birth;
    }

    public function setDateBirth($date_birth): self
    {
        $this->date_birth = $date_birth;

        return $this;
    }

    public function getAboutMe(): ?string
    {
        return $this->about_me;
    }

    public function setAboutMe(?string $about_me): self
    {
        $this->about_me = $about_me;
        return $this;
    }

    public function getPictureProfile(): ?string
    {
        return $this->picture_profile;
    }

    public function setPictureProfile(string $picture_profile): self
    {
        $this->picture_profile = $picture_profile;

        return $this;
    }

    public function getReceivingPatientInfo(): ?string
    {
        return $this->receiving_patient_info;
    }

    public function setReceivingPatientInfo(?string $receiving_patient_info): self
    {
        $this->receiving_patient_info = $receiving_patient_info;

        return $this;
    }

    public function getServices(): ?string
    {
        return $this->services;
    }

    public function setServices(?string $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(?string $specialization): self
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function getCreateAt(): ?DateTime
    {
        return $this->create_at;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function getPriceCustomValue(): ?int
    {
        return $this->price_custom_value;
    }

    public function setPriceCustomValue(?int $price_custom_value): self
    {
        $this->price_custom_value = $price_custom_value;
        return $this;
    }

    public function getPriceType(): ?string
    {
        return $this->price_type;
    }

    public function setPriceType(?string $price_type): self
    {
        $this->price_type = $price_type;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_DOCTOR';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDoctorSocial(): ?DoctorSocial
    {
        return $this->doctorSocial;
    }

    public function setDoctorSocial(?DoctorSocial $doctorSocial): self
    {
        $this->doctorSocial = $doctorSocial;

        return $this;
    }

    /**
     * @return Collection|Timing[]
     */
    public function getTimings(): Collection
    {
        return $this->timings;
    }

    public function addTiming(Timing $timing): self
    {
        if (!$this->timings->contains($timing)) {
            $this->timings[] = $timing;
            $timing->setDoctor($this);
        }

        return $this;
    }

    public function removeTiming(Timing $timing): self
    {
        if ($this->timings->contains($timing)) {
            $this->timings->removeElement($timing);
            // set the owning side to null (unless already changed)
            if ($timing->getDoctor() === $this) {
                $timing->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Clinic[]
     */
    public function getClinics(): Collection
    {
        return $this->clinics;
    }

    public function addClinic(Clinic $clinic): self
    {
        if (!$this->clinics->contains($clinic)) {
            $this->clinics[] = $clinic;
            $clinic->setDoctor($this);
        }

        return $this;
    }

    public function removeClinic(Clinic $clinic): self
    {
        if ($this->clinics->contains($clinic)) {
            $this->clinics->removeElement($clinic);
            // set the owning side to null (unless already changed)
            if ($clinic->getDoctor() === $this) {
                $clinic->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Education[]
     */
    public function getEducations(): Collection
    {
        return $this->educations;
    }

    public function addEducation(Education $education): self
    {
        if (!$this->educations->contains($education)) {
            $this->educations[] = $education;
            $education->setDoctor($this);
        }

        return $this;
    }

    public function removeEducation(Education $education): self
    {
        if ($this->educations->contains($education)) {
            $this->educations->removeElement($education);
            // set the owning side to null (unless already changed)
            if ($education->getDoctor() === $this) {
                $education->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setDoctor($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->contains($experience)) {
            $this->experiences->removeElement($experience);
            // set the owning side to null (unless already changed)
            if ($experience->getDoctor() === $this) {
                $experience->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Awards[]
     */
    public function getAwards(): Collection
    {
        return $this->awards;
    }

    public function addAward(Awards $award): self
    {
        if (!$this->awards->contains($award)) {
            $this->awards[] = $award;
            $award->setDoctor($this);
        }

        return $this;
    }

    public function removeAward(Awards $award): self
    {
        if ($this->awards->contains($award)) {
            $this->awards->removeElement($award);
            // set the owning side to null (unless already changed)
            if ($award->getDoctor() === $this) {
                $award->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Memberships[]
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(Memberships $membership): self
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships[] = $membership;
            $membership->setDoctor($this);
        }

        return $this;
    }

    public function removeMembership(Memberships $membership): self
    {
        if ($this->memberships->contains($membership)) {
            $this->memberships->removeElement($membership);
            // set the owning side to null (unless already changed)
            if ($membership->getDoctor() === $this) {
                $membership->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Registrations[]
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registrations $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations[] = $registration;
            $registration->setDoctor($this);
        }

        return $this;
    }

    public function removeRegistration(Registrations $registration): self
    {
        if ($this->registrations->contains($registration)) {
            $this->registrations->removeElement($registration);
            // set the owning side to null (unless already changed)
            if ($registration->getDoctor() === $this) {
                $registration->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setDoctor($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getDoctor() === $this) {
                $appointment->setDoctor(null);
            }
        }

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getBusinessHours(): ?string
    {
        return $this->business_hours;
    }

    public function setBusinessHours(?string $business_hours): self
    {
        $this->business_hours = $business_hours;

        return $this;
    }

    public function getBusinessHoursArray()
    {
        $data = json_decode($this->business_hours, true);
        if ($data) {
            $data = $data['business-hours'];
        }
        return $data;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getUrlProfile()
    {
        $name = str_replace(' ', '-',  /*str_replace('.', '', $title) .*/ $this->getFirstName() . '-' . $this->getLastName());
        return str_replace('.', '', $name);
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getFormattedAddress() : string
    {
        return '<address>'.$this->getFormattedAddress2().'</address>';
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAddressLine1(): ?string
    {
        return $this->address_line_1;
    }

    public function setAddressLine1(?string $address_line_1): self
    {
        $this->address_line_1 = $address_line_1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->address_line_2;
    }

    public function setAddressLine2(?string $address_line_2): self
    {
        $this->address_line_2 = $address_line_2;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getFormattedAddress2() :string
    {
        $state = $this->getState();
        $city = $this->getCity();
        $address_line_1 = $this->getAddressLine1();
        $address_line_2 = $this->getAddressLine2();
        $code_postale = $this->getPostalCode();
        $formated_address = '';
        if ($address_line_1)
            $formated_address .= $address_line_1 . ', ';
        if ($address_line_2)
            $formated_address .= $address_line_2 . ', ';
        if ($code_postale)
            $formated_address .= $code_postale . ' ';
        if ($city)
            $formated_address .= $city . ' ';
        if ($state)
            $formated_address .= $state;
        return $formated_address;
    }

    public function getDisplayLanguage() : ?string
    {
        $langs = $this->getSpokenLanguages();
        if (is_array($langs)) {
            $final_langs = array_filter($langs, function ($value) {
                return !is_null($value) && $value !== 'Other';
            });
            $lang_other = explode(' ', str_replace('Choose a Language...', '', $this->getLangOther()));
            $final_langs = array_merge($final_langs, $lang_other);
            $final_langs = array_filter($final_langs, function ($value) {
                return !is_null($value) && $value !== '';
            });
            return implode(' | ', $final_langs);
        }
        return null;
    }

    public function getSpokenLanguages(): ?array
    {
        return $this->spoken_languages;
    }

    public function setSpokenLanguages(?array $spoken_languages): self
    {
        $this->spoken_languages = $spoken_languages;

        return $this;
    }

    public function getLangOther(): ?string
    {
        return $this->lang_other;
    }

    public function setLangOther(?string $lang_other): self
    {
        $this->lang_other = $lang_other;

        return $this;
    }


    /** @see \Serializable::serialize() */
    /*public function serialize()
    {
        return serialize(array(
			$this->id,
			$this->first_name,
			$this->last_name,
			$this->gender,
			$this->email,
			$this->password,
			$this->phone_number,
			$this->date_birth,
			$this->about_me,
			$this->address_line_1,
			$this->address_line_2,
			$this->city,
			$this->state,
			$this->country,
			$this->postal_code,
			$this->picture_profile,
			$this->receiving_patient_info,
			$this->price_type,
			$this->price_custom_value,
			$this->speciality,
			$this->services,
			$this->specialization,
			$this->roles,
			$this->create_at,
			$this->updated_at,
			$this->doctorSocial,
			$this->idTiming,
			$this->clinics,
			$this->educations,
			$this->experiences,
			$this->awards,
			$this->memberships,
			$this->registrations,
			$this->appointments,
			$this->business_hours,
			$this->latitude,
			$this->longitude,
			$this->slug,
			$this->spoken_languages,
			$this->title,
			$this->lang_other
        ));
    }*/


}
