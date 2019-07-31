<?php

namespace App\Repository;

use App\Entity\Liturgy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Liturgy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liturgy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liturgy[]    findAll()
 * @method Liturgy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiturgyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Liturgy::class);
    }

    // /**
    //  * @return Liturgy[] Returns an array of Liturgy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Liturgy
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
