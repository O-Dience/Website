<?php

namespace App\Repository;

use App\Entity\UserCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCategory[]    findAll()
 * @method UserCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCategory::class);
    }



    // Know if a relationship exists
    public function getRelation($user, $category)
    {
        
        // je crée un querybuilder sur l'objet User avec l'alias 'user'
        $builder = $this->createQueryBuilder('UserCategory');
     
        $builder->where("UserCategory.user = :user");
        $builder->andWhere("UserCategory.category = :category");
        $builder->andWhere('UserCategory.notification = 1'); // if notification = true
        $builder->setParameter('user', $user);
        $builder->setParameter('category', $category);
        $query = $builder->getQuery();
      
        $result = $query->getResult();
        return $result;
    }

    // /**
    //  * @return UserCategory[] Returns an array of UserCategory objects
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
    public function findOneBySomeField($value): ?UserCategory
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
