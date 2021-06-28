<?php

namespace App\Repository;

use App\Entity\DoctorSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DoctorSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoctorSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoctorSocial[]    findAll()
 * @method DoctorSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorSocialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorSocial::class);
    }

    // /**
    //  * @return DoctorSocial[] Returns an array of DoctorSocial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DoctorSocial
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
