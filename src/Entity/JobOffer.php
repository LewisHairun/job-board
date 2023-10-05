<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobOfferRepository::class)]
class JobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isActivated = null;

    #[ORM\Column(length: 100)]
    private ?string $place = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expiringDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $publicationDate = null;

    #[ORM\ManyToOne(inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\ManyToOne(inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobBranch $jobBranch = null;

    #[ORM\ManyToOne(inversedBy: 'jobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recruiter $recruiter = null;

    #[ORM\OneToMany(mappedBy: 'jobOffer', targetEntity: CandidateJobOffer::class)]
    private Collection $candidateJobOffers;

    public function __construct()
    {
        $this->candidateJobOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getExpiringDate(): ?\DateTimeInterface
    {
        return $this->expiringDate;
    }

    public function setExpiringDate(\DateTimeInterface $expiringDate): static
    {
        $this->expiringDate = $expiringDate;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeImmutable
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeImmutable $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getJobBranch(): ?JobBranch
    {
        return $this->jobBranch;
    }

    public function setJobBranch(?JobBranch $jobBranch): static
    {
        $this->jobBranch = $jobBranch;

        return $this;
    }

    public function getRecruiter(): ?Recruiter
    {
        return $this->recruiter;
    }

    public function setRecruiter(?Recruiter $recruiter): static
    {
        $this->recruiter = $recruiter;

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
            $candidateJobOffer->setJobOffer($this);
        }

        return $this;
    }

    public function removeCandidateJobOffer(CandidateJobOffer $candidateJobOffer): static
    {
        if ($this->candidateJobOffers->removeElement($candidateJobOffer)) {
            // set the owning side to null (unless already changed)
            if ($candidateJobOffer->getJobOffer() === $this) {
                $candidateJobOffer->setJobOffer(null);
            }
        }

        return $this;
    }
}
