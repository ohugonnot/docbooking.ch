<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    // /**
    //  * @return Appointment[] Returns an array of Appointment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findOneByOrderId($value): ?Appointment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.orderID = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
	
	public function findByNotTodayDate($doctor)
    {
		$date = new \DateTime();
        return $this->createQueryBuilder('a')
            ->where('a.doctor = :val')
			->andWhere('a.app_date > :val1')
            ->setParameter('val', $doctor)
			->setParameter('val1', $date->format('Y-m-j'))
            ->getQuery()
            ->getResult()
        ;
    }
	
	public function findByTodayDate($doctor)
    {
		$date = new \DateTime();
        return $this->createQueryBuilder('a')
            ->where('a.doctor = :val')
			->andWhere('a.app_date = :val1')
            ->setParameter('val', $doctor)
			->setParameter('val1', $date->format('Y-m-j'))
            ->getQuery()
            ->getResult()
        ;
    }
	
	public function findByTodayFullDate($doctor)
    {
		$date = new \DateTime();
        return $this->createQueryBuilder('a')
            ->where('a.doctor = :val')
			->andWhere('a.create_time between :val2 AND :val3')
            ->setParameter('val', $doctor)
			->setParameter('val2', $date->format('Y-m-j 00:00:00'))
			->setParameter('val3', $date->format('Y-m-j H:i:s'))
            ->getQuery()
            ->getResult()
        ;
    }
	
	public function findExistAppointment($date_val, $time_val){
        return $this->createQueryBuilder('a')
            ->where('a.app_date = :val')
			->andWhere('a.app_time = :val1')
			->setParameter('val', $date_val)
			->setParameter('val1', $time_val)
            ->getQuery()
            ->getResult()
        ;
	}
	
}
