<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Patient implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private ?string $blood_group;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     */
    private string $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $phone_number;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTime $date_birth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address;

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
     * @ORM\Column(type="simple_array", nullable=false)
     */
    private array $roles = [];

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $insurance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $insurance_num;

    /**
     * @ORM\OneToMany(targetEntity=Appointment::class, cascade={"persist", "remove"}, mappedBy="patient")
     * @ORM\OrderBy({"app_date" = "DESC"})
     */
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    public function __toString()
    {
        $format = "Question (id: %s)\n";
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

    public function getBloodGroup(): ?string
    {
        return $this->blood_group;
    }

    public function setBloodGroup(string $blood_group): self
    {
        $this->blood_group = $blood_group;

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

    public function getDateBirth()
    {
        return $this->date_birth;
    }

    public function setDateBirth($date_birth)
    {
        $this->date_birth = $date_birth;
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

    public function getPictureProfile(): ?string
    {
        return $this->picture_profile;
    }

    public function setPictureProfile(string $picture_profile): self
    {
        $this->picture_profile = $picture_profile;

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
        $roles[] = 'ROLE_PATIENT';

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

    /** @see \Serializable::serialize() */
    public function serialize(): ?string
    {
        return serialize(array(
            $this->id,
            $this->first_name,
            $this->last_name,
            $this->blood_group,
            $this->email,
            $this->password,
            $this->phone_number,
            $this->date_birth,
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->postal_code,
            $this->picture_profile,
            $this->roles,
            $this->create_at,
            $this->updated_at,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) : void
    {
        list (
            $this->id,
            $this->first_name,
            $this->last_name,
            $this->blood_group,
            $this->email,
            $this->password,
            $this->phone_number,
            $this->date_birth,
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->postal_code,
            $this->picture_profile,
            $this->roles,
            $this->create_at,
            $this->updated_at,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, array('allowed_classes' => false));
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
            $appointment->setPatient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getPatient() === $this) {
                $appointment->setPatient(null);
            }
        }

        return $this;
    }

    public function getFormattedAddress2()
    {
        $state = $this->getState();
        $address = $this->getAddress();
        $code_postale = $this->getPostalCode();
        $formated_address = $address . ',' . $code_postale . ' ' . $state;
        if ($formated_address == ', ') {
            return false;
        }
        return $formated_address;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getInsurance(): ?string
    {
        return $this->insurance;
    }

    public function setInsurance(?string $insurance): self
    {
        $this->insurance = $insurance;

        return $this;
    }

    public function getInsuranceNum(): ?string
    {
        return $this->insurance_num;
    }

    public function setInsuranceNum(?string $insurance_num): self
    {
        $this->insurance_num = $insurance_num;

        return $this;
    }
    public function isDoctor() : bool
    {
        return false;
    }
}
