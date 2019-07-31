<?php

namespace App\Repository;

use App\Entity\Headquarter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Headquarter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Headquarter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Headquarter[]    findAll()
 * @method Headquarter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HeadquarterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Headquarter::class);
    }

    // /**
    //  * @return Headquarter[] Returns an array of Headquarter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Headquarter
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}