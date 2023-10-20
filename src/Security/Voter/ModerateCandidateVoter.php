<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ModerateCandidateVoter extends Voter
{
    const MODERATE_CANDIDATE = 'moderate_candidate';

    public function __construct(private Security $security, 
                                private RoleRepository $roleRepository, 
                                private RequestStack $requestStack)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if ($attribute !== self::MODERATE_CANDIDATE) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$this->security->getUser()) {
            return false;
        }

        $routesAuthorized = [];

        $roles = $this->security->getUser()->getRoles();
        $roles = array_diff($roles, ["ROLE_USER"]);

        if (count($roles)) {
            $roleName = $roles[0];
            $role = $this->roleRepository->findOneBy(["name" => $roleName]);
            
            if (count($role->getPermission())) {
                $permissions = $role->getPermission()->toArray();
                
                foreach ($permissions as $permission) {
                    $routesAuthorized[] = $permission->getName();
                }
            }
        }

        $currentRoute = $this->requestStack->getCurrentRequest()->get('_route');

        if (!in_array($currentRoute, $routesAuthorized)) {
            return false;
        } 

        return true;
    }
}
