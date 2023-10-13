<?php

namespace App\Controller;

use App\Filter\JobOfferFilter;
use App\Form\JobOfferFilterType;
use App\Form\SearchType;
use App\Repository\JobOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    public function __construct(private JobOfferRepository $jobOfferRepository, private PaginatorInterface $paginator)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 6;
        $lastestJobOffers =  $this->paginator->paginate($this->jobOfferRepository->findLatestJobOffers(), $page, $limit);

        $formSearch = $this->createForm(SearchType::class);
        $formSearch->handleRequest($request);

        $jobOfferFilter = new JobOfferFilter();
        $formJobOfferFilter = $this->createForm(JobOfferFilterType::class, $jobOfferFilter);
        $formJobOfferFilter->handleRequest($request);

        if ($formSearch->isSubmitted()) {
            $keyword = $formSearch->get("keyword")->getData();
            $lastestJobOffers = $this->paginator->paginate($this->jobOfferRepository->findLatestJobOffers($keyword), $page, $limit);
        }

        if ($formJobOfferFilter->isSubmitted() && $formJobOfferFilter->isValid()) {
            $lastestJobOffers = $this->paginator->paginate($this->jobOfferRepository->filterJobOffer($jobOfferFilter), $page, $limit);
        }

        return $this->render('home/index.html.twig', [
            'lastestJobOffers' => $lastestJobOffers,
            'formSearch' => $formSearch,
            'formJobOfferFilter' => $formJobOfferFilter
        ]);
    }

    #[Route('/job-offers-locations', name: 'job_offers_locations', methods: 'GET')]
    public function findAllJobOffersLocations(): Response
    {
        $locations = $this->jobOfferRepository->findAllJobOffersLocations();

        return $this->json($locations, Response::HTTP_OK, [], ["groups" => "location:job:offer"]);
    }
}
