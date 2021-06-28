<?php

namespace App\Repository;

use App\Entity\Registrations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Registrations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Registrations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Registrations[]    findAll()
 * @method Registrations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registrations::class);
    }

    // /**
    //  * @return Registrations[] Returns an array of Registrations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Registrations
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
