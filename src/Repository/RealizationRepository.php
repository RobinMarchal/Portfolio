<?php

namespace App\Repository;

use App\Entity\Realization;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Realization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Realization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Realization[]    findAll()
 * @method Realization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealizationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Realization::class);
    }

    public function findOnline(): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.online = true')
            ->orderBy('o.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Realization[] Returns an array of Realization objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Realization
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
