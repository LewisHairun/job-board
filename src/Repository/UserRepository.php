<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function countCandidacy(?\DateTime $startDate = null, \DateTime $endDate = null) : int {
        $count = 0;
        $now = (new \DateTimeImmutable())->format("Y-m-d");
        $query = $this->createQueryBuilder("u")
                      ->addSelect("u.id");

        if (isset($startDate) && isset($endDate)) {
            if ($startDate == $endDate) {
                $query->andWhere("u.registeredDate LIKE :date")
                      ->setParameter("date", '%' . $startDate->format("Y-m-d") . '%');
            } else {
                $query->andWhere("u.registeredDate BETWEEN :startDate AND :endDate")
                      ->setParameter("startDate", $startDate)
                      ->setParameter("endDate", $endDate);
            }
        } else {
            $query->andWhere("u.registeredDate LIKE :now")
                  ->setParameter("now", "%" . $now . "%");
        }

        $result = $query->getQuery()->getResult();

        if (count($result)) {
            $count = count($result);
        }

        return $count;
    }
}
