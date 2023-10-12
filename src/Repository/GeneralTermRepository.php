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

    public function getContent(): string
    {
        $data = $this->createQueryBuilder('gt')
                     ->addSelect('gt.content')
                     ->orderBy('gt.id', 'desc') 
                     ->getQuery()
                     ->getOneOrNullResult();
 
         $content = "";            
 
         if (count($data)) {
             $content = $data[0]->getContent();            
         }            
 
        return $content;
    }
}
