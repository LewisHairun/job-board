<?php

namespace App\Entity;

use App\Repository\CandidateJobOfferRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidateJobOfferRepository::class)]
class CandidateJobOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'candidateJobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidate $candidate = null;

    #[ORM\ManyToOne(inversedBy: 'candidateJobOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobOffer $jobOffer = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $candidacyDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->candidate;
    }

    public function setCandidate(?Candidate $candidate): static
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getJobOffer(): ?JobOffer
    {
        return $this->jobOffer;
    }

    public function setJobOffer(?JobOffer $jobOffer): static
    {
        $this->jobOffer = $jobOffer;

        return $this;
    }

    public function getCandidacyDate(): ?\DateTimeImmutable
    {
        return $this->candidacyDate;
    }

    public function setCandidacyDate(\DateTimeImmutable $candidacyDate): static
    {
        $this->candidacyDate = $candidacyDate;

        return $this;
    }
}
