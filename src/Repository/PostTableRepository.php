<?php

namespace App\Repository;

use App\Entity\PostTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PostTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostTable[]    findAll()
 * @method PostTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PostTable::class);
    }

    // /**
    //  * @return PostTable[] Returns an array of PostTable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PostTable
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
