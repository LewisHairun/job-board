<?php

namespace App\Repository;

use App\Entity\PositionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PositionType>
 *
 * @method PositionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PositionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PositionType[]    findAll()
 * @method PositionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PositionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PositionType::class);
    }

//    /**
//     * @return PositionType[] Returns an array of PositionType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PositionType
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
