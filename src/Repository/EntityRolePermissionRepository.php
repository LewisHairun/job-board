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

    public function save(EntityRolePermission $entityRolePermission, bool $flush = false) {
        $this->_em->persist($entityRolePermission);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function saveAll() {
        $this->_em->flush();
    }

//    /**
//     * @return EntityRolePermission[] Returns an array of EntityRolePermission objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EntityRolePermission
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
