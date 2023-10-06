<?php

namespace App\Filter;

use App\Entity\JobBranch;
use Symfony\Component\Validator\Constraints as Assert;

class JobOfferFilter
{
    private ?string $minSalary = null;

    #[Assert\GreaterThan(propertyPath: "minSalary", message: "Le salaire maximale doit être supérieur au salaire minimum")]
    private ?string $maxSalary = null;

    private ?string $orderingCity = null;

    private ?string $orderingJobOffer = null;

    private ?JobBranch $jobBranch = null;

    public function getMinSalary(): ?string
    {
        return $this->minSalary;
    }

    public function setMinSalary(string $minSalary): static
    {
        $this->minSalary = $minSalary;

        return $this;
    }

    public function getOrderingCity(): ?string
    {
        return $this->orderingCity;
    }

    public function setOrderingCity(string $orderingCity): static
    {
        $this->orderingCity = $orderingCity;

        return $this;
    }

    public function getOrderingJobOffer(): ?string
    {
        return $this->orderingJobOffer;
    }

    public function setOrderingJobOffer(string $orderingJobOffer): static
    {
        $this->orderingJobOffer = $orderingJobOffer;

        return $this;
    }

    public function getMaxSalary(): ?string
    {
        return $this->maxSalary;
    }

    public function setMaxSalary(string $maxSalary): static
    {
        $this->maxSalary = $maxSalary;

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
}
