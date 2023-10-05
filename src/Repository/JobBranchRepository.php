<?php

namespace App\Repository;

use App\Entity\JobBranch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobBranch>
 *
 * @method JobBranch|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobBranch|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobBranch[]    findAll()
 * @method JobBranch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobBranchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobBranch::class);
    }

//    /**
//     * @return JobBranch[] Returns an array of JobBranch objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JobBranch
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
