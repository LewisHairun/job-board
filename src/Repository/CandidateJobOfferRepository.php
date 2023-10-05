<?php

namespace App\Repository;

use App\Entity\CandidateJobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CandidateJobOffer>
 *
 * @method CandidateJobOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidateJobOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidateJobOffer[]    findAll()
 * @method CandidateJobOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateJobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidateJobOffer::class);
    }

//    /**
//     * @return CandidateJobOffer[] Returns an array of CandidateJobOffer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CandidateJobOffer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
