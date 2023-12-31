<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    #[Assert\Length(max: 255, maxMessage: "Le prénom ne doit pas dépasser les {{ limit }} caractères")]
    private ?string $firstname = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message: "Le nom de famille est obligatoire")]
    #[Assert\Length(max: 120, maxMessage: "Le nom de famille ne doit pas dépasser les {{ limit }} caractères")]
    private ?string $lastname = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "Veuillez fournir une adresse email valide")]
    #[Assert\Length(max: 180, maxMessage: "L'adresse email ne doit pas dépasser les {{ limit }} caractères")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $degree = null;

    #[ORM\Column()]
    private array $roles = [];

    #[ORM\Column(nullable: true)]
    private ?bool $isActivated = false;

    #[ORM\ManyToOne(inversedBy: 'candidates')]
    private ?PositionType $positionType = null;

    #[ORM\ManyToMany(targetEntity: Skill::class, inversedBy: 'candidates')]
    private Collection $skill;

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: CandidateJobOffer::class)]
    private Collection $candidateJobOffers;

    #[ORM\Column]
    private ?\DateTimeImmutable $registeredDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cvAttachment = null;

    #[ORM\OneToMany(mappedBy: 'candidate', targetEntity: ProfExperience::class)]
    private Collection $profExperiences;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'roles_user')]
    private Collection $role;

    public function __construct()
    {
        $this->skill = new ArrayCollection();
        $this->candidateJobOffers = new ArrayCollection();
        $this->registeredDate = new \DateTimeImmutable();
        $this->profExperiences = new ArrayCollection();
        $this->role = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
    * @see PasswordAuthenticatedUserInterface
    */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDegree(): ?string
    {
        return $this->degree;
    }

    public function setDegree(?string $degree): static
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->role->map(function($role) {
            return $role->getName();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isIsActivated(): ?bool
    {
        return $this->isActivated;
    }

    public function setIsActivated(?bool $isActivated): static
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    public function getPositionType(): ?PositionType
    {
        return $this->positionType;
    }

    public function setPositionType(?PositionType $positionType): static
    {
        $this->positionType = $positionType;

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkill(): Collection
    {
        return $this->skill;
    }

    public function addSkill(Skill $skill): static
    {
        if (!$this->skill->contains($skill)) {
            $this->skill->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static
    {
        $this->skill->removeElement($skill);

        return $this;
    }

    /**
     * @return Collection<int, CandidateJobOffer>
     */
    public function getCandidateJobOffers(): Collection
    {
        return $this->candidateJobOffers;
    }

    public function addCandidateJobOffer(CandidateJobOffer $candidateJobOffer): static
    {
        if (!$this->candidateJobOffers->contains($candidateJobOffer)) {
            $this->candidateJobOffers->add($candidateJobOffer);
            $candidateJobOffer->setCandidate($this);
        }

        return $this;
    }

    public function removeCandidateJobOffer(CandidateJobOffer $candidateJobOffer): static
    {
        if ($this->candidateJobOffers->removeElement($candidateJobOffer)) {
            // set the owning side to null (unless already changed)
            if ($candidateJobOffer->getCandidate() === $this) {
                $candidateJobOffer->setCandidate(null);
            }
        }

        return $this;
    }

    /**
    * @see UserInterface
    */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
    * A visual identifier that represents this user.
    *
    * @see UserInterface
    */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRegisteredDate(): ?\DateTimeImmutable
    {
        return $this->registeredDate;
    }

    public function setRegisteredDate(\DateTimeImmutable $registeredDate): static
    {
        $this->registeredDate = $registeredDate;

        return $this;
    }

    public function getCvAttachment(): ?string
    {
        return $this->cvAttachment;
    }

    public function setCvAttachment(?string $cvAttachment): static
    {
        $this->cvAttachment = $cvAttachment;

        return $this;
    }

    /**
     * @return Collection<int, ProfExperience>
     */
    public function getProfExperiences(): Collection
    {
        return $this->profExperiences;
    }

    public function addProfExperience(ProfExperience $profExperience): static
    {
        if (!$this->profExperiences->contains($profExperience)) {
            $this->profExperiences->add($profExperience);
            $profExperience->setCandidate($this);
        }

        return $this;
    }

    public function removeProfExperience(ProfExperience $profExperience): static
    {
        if ($this->profExperiences->removeElement($profExperience)) {
            // set the owning side to null (unless already changed)
            if ($profExperience->getCandidate() === $this) {
                $profExperience->setCandidate(null);
            }
        }

        return $this;
    }

    public function fullname(): string  
    {
        return "{$this->lastname} {$this->firstname}";
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): static
    {
        if (!$this->role->contains($role)) {
            $this->role->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->role->removeElement($role);

        return $this;
    }
}
