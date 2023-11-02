<?php

namespace App\Twig\Runtime;

use App\Entity\EntityRolePermission;
use Twig\Extension\RuntimeExtensionInterface;
use App\Repository\EntityRolePermissionRepository;

class AuthorizedExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private EntityRolePermissionRepository $entityRolePermissionRepository)
    {
    }

    public function can(string $entityName, string $roleName)
    {
        /** @var EntityRolePermission $entityRolePermission */
        $entityRolePermission = $this->entityRolePermissionRepository->findOneBy(["entityName" => $entityName, "roleName" => $roleName]);

        if (null === $entityRolePermission) {
            return false;
        }

        if (null !== $entityRolePermission->isCanAdd() || 0 !== $entityRolePermission->isCanAdd()) {
            return $entityRolePermission->isCanAdd();
        }

        if (null !== $entityRolePermission->isCanView() || 0 !== $entityRolePermission->isCanView()) {
            return $entityRolePermission->isCanView();
        }

        if (null !== $entityRolePermission->isCanEdit() || 0 !== $entityRolePermission->isCanEdit()) {
            return $entityRolePermission->isCanEdit();
        }

        if (null !== $entityRolePermission->isCanDelete() || 0 !== $entityRolePermission->isCanDelete()) {
            return $entityRolePermission->isCanDelete();
        }

        return false;
    }
}
