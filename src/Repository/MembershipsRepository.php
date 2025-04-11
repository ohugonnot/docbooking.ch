<?php

namespace App\Repository;

use App\Entity\Memberships;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Memberships|null find($id, $lockMode = null, $lockVersion = null)
 * @method Memberships|null findOneBy(array $criteria, array $orderBy = null)
 * @method Memberships[]    findAll()
 * @method Memberships[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Memberships::class);
    }

    // /**
    //  * @return Memberships[] Returns an array of Memberships objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Memberships
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
