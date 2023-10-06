<?php

namespace App\Controller;

use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(private JobOfferRepository $jobOfferRepository)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $lastestJobOffers = $this->jobOfferRepository->findBy([], ["publicationDate" => "desc"], 6);

        return $this->render('home/index.html.twig', compact('lastestJobOffers'));
    }
}
