<?php

namespace App\Repository;

use App\Entity\JobOffer;
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
                        ->addSelect('jo.title', 'jo.description', 'pt.type') 
                        ->leftJoin('jo.positionType', 'pt'); 

        if ($keyword) {
            $query->orWhere('jo.title LIKE :keyword')
                    ->orWhere('jo.description LIKE :keyword')
                    ->orWhere('pt.type LIKE :keyword')
                    ->setParameter('keyword', '%' . $keyword . '%');
        } 
                        
       return 
           
           $query->orderBy('jo.publicationDate', "DESC")
           ->setMaxResults(6)
           ->getQuery()
           ->getResult()
       ;
   }
}
