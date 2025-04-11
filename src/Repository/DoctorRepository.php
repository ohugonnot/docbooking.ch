<?php

namespace App\Repository;

use App\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Doctor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doctor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doctor[]    findAll()
 * @method Doctor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }
	
/*public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null){
		$persister = $this->_em->getUnitOfWork()->getEntityPersister($this->_entityName);
		return $persister->loadAll($criteria, $orderBy, $limit, $offset);
	}*/

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Doctor) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
	
	
	public function getEntityManager(){
		return $this->_em;
	}
	
	public function findOneBySlugField($slug): ?Doctor
    {
        return $this->createQueryBuilder('d')
			->where('d.slug= :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
	
	public function findOneByEmailField($email): ?Doctor
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Doctor[] Returns an array of Doctor objects
    //  */
    public function findBySearchField($q)
    {
        return $this->createQueryBuilder('d')
            ->orWhere('d.first_name LIKE :searchTerm')
			->orWhere('d.last_name LIKE :searchTerm')
			->setParameter('searchTerm', '%' . $q . '%')
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function findByNow()
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.timings', 't')
            ->where('t.year >= :year and t.month >= :month and t.day >= :day')
            ->orWhere("t.year > :year")
            ->setParameter('year', (new \DateTime())->format("y"))
            ->setParameter('month', (new \DateTime())->format("n"))
            ->setParameter('day', (new \DateTime())->format("j"))
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByLastAppointement()
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.appointments', 'a')
            ->groupBy('d.id')
            ->orderBy('a.create_time', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Doctor
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
