<?php

namespace App\Repository;

use App\Entity\LegalNotice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LegalNotice>
 *
 * @method LegalNotice|null find($id, $lockMode = null, $lockVersion = null)
 * @method LegalNotice|null findOneBy(array $criteria, array $orderBy = null)
 * @method LegalNotice[]    findAll()
 * @method LegalNotice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LegalNoticeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LegalNotice::class);
    }

   public function getContent(): string
   {
       $data = $this->createQueryBuilder('l')
                    ->addSelect('l.content')
                    ->orderBy('l.id', 'desc') 
                    ->getQuery()
                    ->getOneOrNullResult();

        $content = "";            

        if (count($data)) {
            $content = $data[0]->getContent();            
        }            

       return $content;
   }
}
