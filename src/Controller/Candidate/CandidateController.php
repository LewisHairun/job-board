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

    #[Route('/candidat', name: 'candidate_index')]
    public function index(): Response
    {
        return $this->render('candidate/index.html.twig', []);
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

        return $this->redirectToRoute("app_home");
    }
}
