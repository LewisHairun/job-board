<?php

namespace App\Repository;

use App\Entity\EntityRolePermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EntityRolePermission>
 *
 * @method EntityRolePermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntityRolePermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntityRolePermission[]    findAll()
 * @method EntityRolePermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityRolePermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityRolePermission::class);
    }

    public function reset() {
        $this->createQueryBuilder("erp")
                        ->update()
                        ->set('erp.canAdd', ':value')
                        ->set('erp.canEdit', ':value')
                        ->set('erp.canView', ':value')
                        ->set('erp.canDelete', ':value')
                        ->setParameter('value', false)
                        ->getQuery()
                        ->execute();

    }
}
