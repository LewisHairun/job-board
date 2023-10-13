<?php

namespace App\Controller\Candidate;

use App\Entity\Candidate;
use App\Form\Candidate\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CandidateRegistrationController extends AbstractController
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher, 
                                private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/candidat/inscription', name: 'candidate_registration')]
    public function register(Request $request): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(RegistrationFormType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $candidate->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $candidate,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->entityManager->persist($$candidate);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('candidate/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
