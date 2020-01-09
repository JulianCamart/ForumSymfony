<?php

namespace App\Repository;

use App\Entity\PrivateMessageTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PrivateMessageTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateMessageTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateMessageTable[]    findAll()
 * @method PrivateMessageTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrivateMessageTableRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PrivateMessageTable::class);
    }

    /**
     * @param $userId
     * @return PrivateMessageTable[] Returns an array of PrivateMessageTable objects
     */
    public function findMessageReceive($userId): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.MessageReceiver = :userId')
            ->AndWhere('p.archivedByReceiver = false')
            ->AndWhere('p.deletedByReceiver = false')
            ->setParameter('userId', $userId)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $userId
     * @return PrivateMessageTable[] Returns an array of PrivateMessageTable objects
     */
    public function findMessageSend($userId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.MessageAuthor = :userId')
            ->AndWhere('p.archivedByAuthor = false')
            ->AndWhere('p.deletedByAuthor = false')
            ->setParameter('userId', $userId)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $userId
     * @return PrivateMessageTable[] Returns an array of PrivateMessageTable objects
     */
    public function findArchivedMessageReceive($userId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.MessageReceiver = :userId')
            ->AndWhere('p.archivedByReceiver = true')
            ->AndWhere('p.deletedByReceiver = false')
            ->setParameter('userId', $userId)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $userId
     * @return PrivateMessageTable[] Returns an array of PrivateMessageTable objects
     */
    public function findArchivedMessageSend($userId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.MessageAuthor = :userId')
            ->AndWhere('p.archivedByAuthor = true')
            ->AndWhere('p.deletedByAuthor = false')
            ->setParameter('userId', $userId)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $userId
     * @return PrivateMessageTable[] Returns an array of PrivateMessageTable objects
     */
    public function findDeletedMessageReceive($userId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.MessageReceiver = :userId')
            ->AndWhere('p.deletedByReceiver = true')
            ->setParameter('userId', $userId)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $userId
     * @return PrivateMessageTable[] Returns an array of PrivateMessageTable objects
     */
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


