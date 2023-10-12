<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralTermController extends AbstractController
{
    #[Route('/condition-general-vente', name: 'app_cgu')]
    public function index(): Response
    {
        return $this->render('general_term/index.html.twig');
    }
}
