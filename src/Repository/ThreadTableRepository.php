<?php

namespace App\Repository;

use App\Entity\ThreadTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ThreadTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThreadTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThreadTable[]    findAll()
 * @method ThreadTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ThreadTable::class);
    }

    // /**
    //  * @return ThreadTable[] Returns an array of ThreadTable objects
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
    public function findOneBySomeField($value): ?ThreadTable
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
