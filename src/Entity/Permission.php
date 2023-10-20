<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    #[Assert\NotBlank(message: "Le nom est obligatoire")]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Role::class, mappedBy: 'permission')]
    private Collection $rolePermission;

    public function __construct()
    {
        $this->rolePermission = new ArrayCollection();
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
     * @return Collection<int, Role>
     */
    public function getRolePermission(): Collection
    {
        return $this->rolePermission;
    }

    public function addRolePermission(Role $rolePermission): static
    {
        if (!$this->rolePermission->contains($rolePermission)) {
            $this->rolePermission->add($rolePermission);
            $rolePermission->addPermission($this);
        }

        return $this;
    }

    public function removeRolePermission(Role $rolePermission): static
    {
        if ($this->rolePermission->removeElement($rolePermission)) {
            $rolePermission->removePermission($this);
        }

        return $this;
    }
}
