<?php

namespace App\Repository;

use App\Entity\Timing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Timing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timing[]    findAll()
 * @method Timing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timing::class);
    }
	
	public function findByDateField($day, $month, $year, $doctor)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.day = :day')
            ->andWhere('t.month = :month')
			->andWhere('t.year = :year')
			->andWhere('t.doctor = :doctor')
			->setParameter('day', $day)
			->setParameter('month', $month)
			->setParameter('year', $year)
			->setParameter('doctor', $doctor)
            //->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Timing[] Returns an array of Timing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Timing
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
