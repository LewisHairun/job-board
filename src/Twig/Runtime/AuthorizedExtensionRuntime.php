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

        if (null !== $entityRolePermission->isCanAdd()) {
            return true;
        }

        if (null !== $entityRolePermission->isCanEdit()) {
            return true;
        }

        if (null !== $entityRolePermission->isCanEdit()) {
            return true;
        }

        if (null !== $entityRolePermission->isCanDelete()) {
            return true;
        }

        return false;
    }
}
