<?php

namespace App\Controller\Candidate;

use App\Entity\Candidate;
use App\Entity\CandidateJobOffer;
use App\Entity\JobOffer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/postuler/{id}/{slug}', name: 'apply_job_offer')]
    public function applyJobOffer(JobOffer $jobOffer): Response
    {
        /** @var Candidate $candidate */
        $candidate = $this->getUser();
        $registeredDate = new \DateTimeImmutable();
        $candidateJobOffer = new CandidateJobOffer;
        $candidateJobOffer->setCandidate($candidate);
        $candidateJobOffer->setJobOffer($jobOffer);
        $candidateJobOffer->setCandidacyDate($registeredDate);

        $this->entityManager->persist($candidateJobOffer);
        $this->entityManager->flush();

        $this->addFlash("success", "Votre candidature a été envoyé avec succès");

        return $this->redirectToRoute("app_home");
    }
}
