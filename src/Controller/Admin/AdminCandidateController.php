<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminCandidateController extends AbstractController
{
    public function __construct(private UserRepository $userRepository,
                                private PaginatorInterface $paginator,
                                private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/admin/candidats', name: 'admin_candidate')]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 10;
        $candidates = $this->paginator->paginate($this->userRepository->findBy([], ["lastname" => "desc"]), $page, $limit); 

        return $this->render('admin/candidate/index.html.twig', [
            "candidates" => $candidates
        ]);
    }

    #[Route('/admin/moderation/candidat/{id}', name: 'admin_moderation_candidate')]
    #[IsGranted("moderate_candidate", "candidate")]
    public function moderation(User $candidate): Response
    {
        $isActivated = $candidate->isIsActivated();
        $candidate->setIsActivated(!$isActivated); 
        $this->entityManager->persist($candidate);
        $this->entityManager->flush();

        $message = "";
        
        if ($candidate->isIsActivated()) {
            $message = "Le candidat a été approuvé avec succès"; 
        } else {
            $message = "Le candidat a été désapprouvé avec succès"; 
        }
        
        $this->addFlash("success", $message);

        return $this->redirectToRoute("admin_candidate");
    }
}
