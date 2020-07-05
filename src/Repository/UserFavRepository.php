<?php

namespace App\Repository;

use App\Entity\UserFav;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserFav|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFav|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFav[]    findAll()
 * @method UserFav[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFavRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFav::class);
    }

    // /**
    //  * @return UserFav[] Returns an array of UserFav objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserFav
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
