<?php

namespace App\Repository;

use App\Entity\JobOffer;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobOffer>
 *
 * @method JobOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobOffer[]    findAll()
 * @method JobOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }

   /**
    * @return JobOffer[] Returns an array of JobOffer objects
    */
   public function findLatestJobOffers(string $keyword = null): array
   {
        $query = $this->createQueryBuilder('jo')
                        ->addSelect('jo.id', 'jo.slug', 'jo.title', 'jo.description', 'pt.type', 'c.name AS cityName', 'jb.name AS jobBranchName', 'jo.minSalary', 'jo.maxSalary') 
                        ->leftJoin('jo.city', 'c')
                        ->leftJoin('jo.positionType', 'pt')
                        ->leftJoin('jo.jobBranch', 'jb'); 

        if ($keyword) {
            $query
                ->orWhere('jo.title LIKE :keyword')
                ->orWhere('jo.description LIKE :keyword')
                ->orWhere('pt.type LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%');
        } 
                        
       return $query
                ->orderBy('jo.publicationDate', "DESC")
                ->getQuery()
                ->getResult();
   }

   public function filterJobOffer($jobOfferFilter, string $orderingCity = "desc", string $orderingJobOffer = "desc"): array
   {
        $query = $this->createQueryBuilder('jo')
                        ->addSelect('jo.id', 'jo.slug', 'jo.title', 'jo.description', 'pt.type', 'c.name AS cityName', 'jb.name AS jobBranchName', 'jo.minSalary', 'jo.maxSalary') 
                        ->leftJoin('jo.city', 'c')
                        ->leftJoin('jo.positionType', 'pt')
                        ->leftJoin("jo.jobBranch", "jb"); 

        if ($jobOfferFilter->getMinSalary() && $jobOfferFilter->getMaxSalary() ) {
            $query
                ->andWhere("jo.minSalary >= :minSalary")
                ->andWhere("jo.maxSalary >= :maxSalary")
                ->setParameter("minSalary", $jobOfferFilter->getMinSalary())
                ->setParameter("maxSalary", $jobOfferFilter->getMaxSalary());
        } 

        if ($jobOfferFilter->getJobBranch()) {
            $query 
                ->orWhere("jb.name LIKE :name")
                ->setParameter("name", "%" . $jobOfferFilter->getJobBranch()->getName() . "%");
        }

        $orderingCity = "desc";

        if ($jobOfferFilter->getOrderingCity()) {
            $orderingCity = $jobOfferFilter->getOrderingCity();
            $query->orderBy("c.name", $orderingCity);
        } 

        $orderingJobOffer = "desc";

        if ($jobOfferFilter->getOrderingJobOffer()) {
            $orderingJobOffer = $jobOfferFilter->getOrderingJobOffer();
            $query->orderBy("jo.publicationDate", $orderingJobOffer);
        }

        return $query
                ->getQuery()
                ->getResult();
   }

   public function findAllJobOffersLocations(): array
   {
        $query = $this->createQueryBuilder('jo')
                    ->addSelect('jo.id', 'jo.slug', 'jo.latitude', 'jo.longitude', 'c.name AS cityName', 'pt.type')
                    ->leftJoin('jo.city', 'c')
                    ->leftJoin('jo.positionType', 'pt');

        return $query->getQuery()->getResult();
   }

    public function countOfferPublished(?\DateTime $startDate = null, \DateTime $endDate = null) : int {
        $count = 0;
        $now = (new \DateTimeImmutable())->format("Y-m-d");
        $query = $this->createQueryBuilder("jo")
                      ->addSelect("jo.id");

        if (isset($startDate) && isset($endDate)) {
            if ($startDate == $endDate) {
                $query->andWhere("jo.publicationDate LIKE :date")
                      ->setParameter("date", '%' . $startDate->format("Y-m-d") . '%');
            } else {
                $query->andWhere("jo.publicationDate BETWEEN :startDate AND :endDate")
                      ->setParameter("startDate", $startDate)
                      ->setParameter("endDate", $endDate);
            }
        } else {
            $query->andWhere("jo.publicationDate LIKE :now")
                  ->setParameter("now", "%" . $now . "%");
        }

        $result = $query->getQuery()->getResult();

        if (count($result)) {
            $count = count($result);
        }

        return $count;
    }
}
