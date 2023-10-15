<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOfferController extends AbstractController
{
    #[Route('/admin/offres', name: 'admin_offer')]
    public function index(): Response
    {
        return $this->render('admin/offer/index.html.twig', []);
    }
}
