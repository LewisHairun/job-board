<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCandidateController extends AbstractController
{
    #[Route('/admin/candidats', name: 'admin_candidate')]
    public function index(): Response
    {
        return $this->render('admin/candidate/index.html.twig', []);
    }
}
