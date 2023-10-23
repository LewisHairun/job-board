<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Role>
 *
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function getRoleNames()
    {
        $result = $this->createQueryBuilder("r")
                    ->addSelect("r.name")
                    ->getQuery()
                    ->getResult();

        $roleNames = [];            

        if (count($result)) {
            foreach ($result as $item) {
                $roleNames[] = $item[0]->getName();
            }    
        }
        
        return $roleNames;
    }
}
