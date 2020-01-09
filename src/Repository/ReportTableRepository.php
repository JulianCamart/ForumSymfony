<?php

namespace App\Repository;

use App\Entity\ReportTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReportTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportTable[]    findAll()
 * @method ReportTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReportTable::class);
    }
    

  //  /**
  //   * @param $userId
  //   * @return ReportTable[] Returns an array of ReportTable objects
  //   */
  /*
    public function findDeletedMessageSend($userId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.MessageAuthor = :userId')
            ->AndWhere('p.deletedByAuthor = true')
            ->setParameter('userId', $userId)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?CatTable
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}


