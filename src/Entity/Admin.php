<?php

namespace App\Entity;

use App\Entity\Factorisation\UserTrait;
use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AdminRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Admin implements UserInterface
{
    use UserTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    public function getRoles(): ?array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_ADMIN';
        return array_unique($roles);
    }
    public function isDoctor() : bool
    {
        return false;
    }
    public function isPatient() : bool
    {
        return false;
    }
    public function isAdmin() : bool
    {
        return true;
    }
}
