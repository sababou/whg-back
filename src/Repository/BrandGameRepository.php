<?php

namespace App\Repository;

use App\Entity\BrandGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BrandGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrandGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrandGame[]    findAll()
 * @method BrandGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrandGame::class);
    }

    public function findPopulated(){
      return $this->createQueryBuilder('b')
          ->groupBy('b.brand')
          ->orderBy('b.id', 'ASC')
          ->getQuery()
          ->getResult()
      ;
    }

    public function isHot($launchcode){
      $sum = $this->createQueryBuilder('b')
          ->select("sum(b.hot)")
          ->andWhere('b.launchcode = :val')
          ->setParameter('val', $launchcode)
          ->getQuery()
          ->getSingleScalarResult()
      ;
      if($sum > 0){
        return 1;
      }else{
        return 0;
      }
    }

    public function isNew($launchcode){
      $sum = $this->createQueryBuilder('b')
          ->select("sum(b.new)")
          ->andWhere('b.launchcode = :val')
          ->setParameter('val', $launchcode)
          ->getQuery()
          ->getSingleScalarResult()
      ;
      if($sum > 0){
        return 1;
      }else{
        return 0;
      }
    }



    public function getCategoryList(): array
    {
      $games = $this->createQueryBuilder('b')
          ->groupBy('b.category')
          ->orderBy('b.category', 'ASC')
          ->getQuery()
          ->getResult()
      ;

      $categories = array();
      foreach($games as $game){
        $categories[] = $game->getCategory();
      }

      return $categories;
    }


    // /**
    //  * @return BrandGame[] Returns an array of BrandGame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BrandGame
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
