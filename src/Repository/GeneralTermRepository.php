<?php

namespace App\Repository;

use App\Entity\GeneralTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GeneralTerm>
 *
 * @method GeneralTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeneralTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeneralTerm[]    findAll()
 * @method GeneralTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneralTermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeneralTerm::class);
    }

//    /**
//     * @return GeneralTerm[] Returns an array of GeneralTerm objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GeneralTerm
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
