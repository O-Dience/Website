<?php

namespace App\Repository;

use App\Entity\UserSocial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserSocial|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSocial|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSocial[]    findAll()
 * @method UserSocial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSocialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSocial::class);
    }

    // /**
    //  * @return UserSocial[] Returns an array of UserSocial objects
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
    public function findOneBySomeField($value): ?UserSocial
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
