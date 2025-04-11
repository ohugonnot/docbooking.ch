<?php

namespace App\Repository;

use App\Entity\PatientResetPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * @method PatientResetPasswordRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientResetPasswordRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientResetPasswordRequest[]    findAll()
 * @method PatientResetPasswordRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatientResetPasswordRequest::class);
    }

    public function createResetPasswordRequest(
        object $user,
        \DateTimeInterface $expiresAt,
        string $selector,
        string $hashedToken
    ): ResetPasswordRequestInterface {
        return new PatientResetPasswordRequest(
            $user,
            $expiresAt,
            $selector,
            $hashedToken
        );
    }
}
