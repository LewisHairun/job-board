<?php

namespace App\Controller\Candidate;

use App\Entity\Candidate;
use App\Form\Candidate\UpdateProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/candidat/profile', name: 'candidate_profile')]
    public function index(): Response
    {
        return $this->render('candidate/profile.html.twig', []);
    }

    #[Route('/candidat/parametrage', name: 'candidate_setting')]
    public function setting(Request $request): Response
    {
        $candidate = $this->getUser();
        $form = $this->createForm(UpdateProfileType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($candidate);
            $this->entityManager->flush();

            return $this->redirectToRoute("candidate_profile");
        }

        return $this->render('candidate/setting.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
