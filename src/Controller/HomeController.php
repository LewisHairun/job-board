<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(private JobOfferRepository $jobOfferRepository)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $lastestJobOffers = $this->jobOfferRepository->findLatestJobOffers();
        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);

        if ($formSearch->isSubmitted()) {
            $keyword = $formSearch->get("keyword")->getData();
            $lastestJobOffers = $this->jobOfferRepository->findLatestJobOffers($keyword);   
        }

        return $this->render('home/index.html.twig', [
            'lastestJobOffers' => $lastestJobOffers,
            'formSearch' => $formSearch
        ]);
    }
}
