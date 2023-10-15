<?php

namespace App\Repository;

use App\Entity\Candidate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Candidate>
 *
 * @method Candidate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidate[]    findAll()
 * @method Candidate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidate::class);
    }

    public function countCandidacy(?\DateTime $startDate = null, \DateTime $endDate = null) : int {
        $count = 0;
        $now = (new \DateTimeImmutable())->format("Y-m-d");
        $query = $this->createQueryBuilder("c")
                      ->addSelect("c.id");

        if (isset($startDate) && isset($endDate)) {
            if ($startDate == $endDate) {
                $query->andWhere("c.registeredDate LIKE :date")
                      ->setParameter("date", '%' . $startDate->format("Y-m-d") . '%');
            } else {
                $query->andWhere("c.registeredDate BETWEEN :startDate AND :endDate")
                      ->setParameter("startDate", $startDate)
                      ->setParameter("endDate", $endDate);
            }
        } else {
            $query->andWhere("c.registeredDate LIKE :now")
                  ->setParameter("now", "%" . $now . "%");
        }

        $result = $query->getQuery()->getResult();

        if (count($result)) {
            $count = count($result);
        }

        return $count;
    }
}
