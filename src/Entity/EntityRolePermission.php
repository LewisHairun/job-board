<?php

namespace App\Entity;

use App\Repository\EntityRolePermissionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntityRolePermissionRepository::class)]
class EntityRolePermission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $entityName = null;

    #[ORM\Column(nullable: true)]
    private ?bool $canAdd = null;

    #[ORM\Column(nullable: true)]
    private ?bool $canEdit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $canView = null;

    #[ORM\Column(nullable: true)]
    private ?bool $canDelete = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $roleName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityName(): ?string
    {
        return $this->entityName;
    }

    public function setEntityName(string $entityName): static
    {
        $this->entityName = $entityName;

        return $this;
    }

    public function isCanAdd(): ?bool
    {
        return $this->canAdd;
    }

    public function setCanAdd(?bool $canAdd): static
    {
        $this->canAdd = $canAdd;

        return $this;
    }

    public function isCanEdit(): ?bool
    {
        return $this->canEdit;
    }

    public function setCanEdit(?bool $canEdit): static
    {
        $this->canEdit = $canEdit;

        return $this;
    }

    public function isCanView(): ?bool
    {
        return $this->canView;
    }

    public function setCanView(?bool $canView): static
    {
        $this->canView = $canView;

        return $this;
    }

    public function isCanDelete(): ?bool
    {
        return $this->canDelete;
    }

    public function setCanDelete(?bool $canDelete): static
    {
        $this->canDelete = $canDelete;

        return $this;
    }

    public function getRoleName(): ?string
    {
        return $this->roleName;
    }

    public function setRoleName(?string $roleName): static
    {
        $this->roleName = $roleName;

        return $this;
    }
}
