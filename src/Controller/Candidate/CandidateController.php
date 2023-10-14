<?php

namespace App\Controller\Candidate;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    #[Route('/candidat', name: 'candidate_index')]
    public function index(): Response
    {
        return $this->render('candidate/index.html.twig', [
            
        ]);
    }
}
