<?php

namespace App\Repository;

use App\Entity\TextsDelivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TextsDelivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextsDelivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextsDelivery[]    findAll()
 * @method TextsDelivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextsDeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextsDelivery::class);
    }

    // /**
    //  * @return TextsDelivery[] Returns an array of TextsDelivery objects
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
    public function findOneBySomeField($value): ?TextsDelivery
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
