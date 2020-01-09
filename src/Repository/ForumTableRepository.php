<?php

namespace App\Repository;

use App\Entity\ForumTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumTable[]    findAll()
 * @method ForumTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumTable::class);
    }

    /* public function findNombreDeThread($nbThread): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT (f.forum.threads.id)
            FROM App\Entity\ForumTable f
            WHERE f.forum.threads.id = :nbThread'
        )->setParameter('nbThread', $nbThread);

        // returns an array of Product objects
        return $query->execute();
    } */

   

    // /**
    //  * @return ForumTable[] Returns an array of ForumTable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForumTable
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
