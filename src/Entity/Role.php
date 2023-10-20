<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le rôle est obligatoire")]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'role')]
    private Collection $rolesUser;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'rolePermission')]
    private Collection $permission;

    public function __construct()
    {
        $this->rolesUser = new ArrayCollection();
        $this->permission = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getRolesUser(): Collection
    {
        return $this->rolesUser;
    }

    public function addRolesUser(User $rolesUser): static
    {
        if (!$this->rolesUser->contains($rolesUser)) {
            $this->rolesUser->add($rolesUser);
            $rolesUser->addRole($this);
        }

        return $this;
    }

    public function removeRolesUser(User $rolesUser): static
    {
        if ($this->rolesUser->removeElement($rolesUser)) {
            $rolesUser->removeRole($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Permission>
     */
    public function getPermission(): Collection
    {
        return $this->permission;
    }

    public function addPermission(Permission $permission): static
    {
        if (!$this->permission->contains($permission)) {
            $this->permission->add($permission);
        }

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        $this->permission->removeElement($permission);

        return $this;
    }
}
