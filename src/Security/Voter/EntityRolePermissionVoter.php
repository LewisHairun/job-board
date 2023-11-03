<?php

namespace App\Security\Voter;

use App\Repository\RoleRepository;
use App\Entity\EntityRolePermission;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\EntityRolePermissionRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EntityRolePermissionVoter extends Voter
{
    public const ADD = 'Add';
    public const VIEW = 'View';
    public const EDIT = 'Edit';
    public const DELETE = 'Delete';
    private const METHOD_ALLOWED = [self::ADD, self::VIEW, self::EDIT, self::DELETE]; 

    public function __construct(private Security $security, 
                                private RoleRepository $roleRepository,
                                private EntityRolePermissionRepository $entityRolePermissionRepository)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, self::METHOD_ALLOWED);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user && !$user instanceof UserInterface) {
            return false;
        }

        $roles = $user->getRoles();
        
        $entity = get_class($subject);
        $entityName = str_replace("App\\Entity\\", "", $entity);

        if (count($roles) < 1) return false;

        $roleName = $roles[0];
        $role = $this->roleRepository->findOneBy(["name" => $roleName]);
        /** @var EntityRolePermission $entityRolePermission */
        $entityRolePermission = $this->entityRolePermissionRepository->findOneBy(["role" => $role, "entityName" => $entityName]);

        $method = "isCan$attribute";

        return (bool) $entityRolePermission->$method();
    }
}
