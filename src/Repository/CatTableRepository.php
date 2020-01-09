<?php

namespace App\Repository;

use App\Entity\CatTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CatTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatTable[]    findAll()
 * @method CatTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CatTable::class);
    }

    // /**
    //  * @return CatTable[] Returns an array of CatTable objects
    // */
    

    /*
    public function FindIdByName($value)
    {
        return $this->createQueryBuilder('C.CatId')
            ->from('CatTable', 'C')
            ->where('CatTable.CatName = :CatName')
            ->setParameter('CatName', $value)
            

            //->setParameter('val', $value)

            ->getQuery()
            ->getResult()
        ;
    }
    */
    

    /*
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


