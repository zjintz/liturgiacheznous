<?php

namespace App\Repository;

use App\Entity\EmailSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EmailSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailSubscription[]    findAll()
 * @method EmailSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmailSubscription::class);
    }

    // /**
    //  * @return EmailSubscription[] Returns an array of EmailSubscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailSubscription
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
