<?php

namespace App\Controller\Admin;

use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminOfferController extends AbstractController
{
    public function __construct(private JobOfferRepository $jobOfferRepository,
                                private PaginatorInterface $paginator,
                                private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/admin/offres', name: 'admin_offer')]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 10;
        $offers = $this->paginator->paginate($this->jobOfferRepository->findBy([], ["title" => "desc"]), $page, $limit); 

        return $this->render('admin/offer/index.html.twig', [
            "offers" => $offers
        ]);
    }

    #[Route('/admin/moderation/offre/{id}', name: 'admin_moderation_offer')]
    public function moderation(JobOffer $jobOffer): Response
    {
        $isActivated = $jobOffer->isIsActivated();
        $jobOffer->setIsActivated(!$isActivated); 
        $this->entityManager->persist($jobOffer);
        $this->entityManager->flush();

        $message = "";
        
        if ($jobOffer->isIsActivated()) {
            $message = "L'offre a été approuvé avec succès"; 
        } else {
            $message = "L'offre a été désapprouvé avec succès"; 
        }
        
        $this->addFlash("success", $message);

        return $this->redirectToRoute("admin_offer");
    }
}
