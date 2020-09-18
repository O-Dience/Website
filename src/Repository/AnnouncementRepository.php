<?php

namespace App\Repository;

use App\Entity\Announcement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Announcement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announcement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announcement[]    findAll()
 * @method Announcement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnouncementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    // /**
    //  * @return Announcement[] Returns an array of Announcement objects
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

    /**
     * @return Announcement[] Finds all announcements
     */
    public function findAll(){

        return $this->findBy([],['updated_at'=>'DESC']);
    }

    /**
     * @return Announcement[] Finds all announcements posted by the user represented by this id
     */
    public function findByBrandId($id)
    {
        return $this->createQueryBuilder('announcement')
            ->where('announcement.user = :id')
            ->setParameter('id', $id)
            ->leftJoin('announcement.categories', 'category')
            ->addSelect('category')
            ->leftJoin('announcement.socialNetworks', 'network')
            ->addSelect('network')
            ->getQuery()
            ->execute();
    }

    public function findByInfluencerId($id)
    {
        /*return $this->createQueryBuilder('announcement')
            ->leftJoin('announcement.likedByUsers', 'user')
            ->where('user.favorites = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();*/
        $qb = $this->createQueryBuilder('announcement')
        ->leftJoin('announcement.id', 'id')
        ->addSelect('fav')
        ->leftJoin('announcement.user', 'user')
        ->addSelect('user')
        ->where('user.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->execute();
        return $qb;
    }




   
    public function searchByTitle($title){
        $builder = $this->createQueryBuilder('announcement');
        $builder->where(
            $builder->expr()->like('announcement.title', ":title")
        );
        $builder->setParameter('title', "%$title%");
        $builder->orderBy('announcement.updated_at', 'DESC');
        $query = $builder->getQuery();
        $result = $query->execute();
        return $result;
    }

 
    public function searchByContent($content){
        $builder = $this->createQueryBuilder('announcement');
        $builder->where(
            $builder->expr()->like('announcement.content', ":content")
        );
        $builder->setParameter('content', "%$content%");
        $builder->orderBy('announcement.updated_at', 'DESC');
        $query = $builder->getQuery();
        $result = $query->execute();
        return $result;
        
    }


    public function searchByUsername($username){

        $builder = $this->createQueryBuilder('announcement');

        $builder->leftJoin('announcement.user', "user");

        $builder->addSelect('user');

        $builder->where(
            $builder->expr()->like('user.username', ':username')
        );

        $builder->setParameter('username', "$username%");

        $builder->orderBy('announcement.updated_at', 'DESC');

        $query = $builder->getQuery();

        $result = $query->execute();
        
        return $result;
        
    }

    // /**
    //  * @return Announcement[] Finds all announcements posted by the user represented by this id
    //  */
    // public function showMoreAnnouncements($id)
    // {
    //     return $this->createQueryBuilder('announcement')
    //         ->where('announcement.user = :id')
    //         ->setParameter('id', $id)
    //         ->leftJoin('announcement.categories', 'category')
    //         ->addSelect('category')
    //         ->leftJoin('announcement.socialNetworks', 'network')
    //         ->addSelect('network')
    //         ->setMaxResults(3)
    //         ->getQuery()
    //         ->execute();
    // }


  



    /*
    public function findOneBySomeField($value): ?Announcement
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
