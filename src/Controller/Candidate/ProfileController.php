<?php

namespace App\Controller\Candidate;

use App\Contract\FileUploaderInterface;
use App\Entity\Candidate;
use App\Form\Candidate\UpdateProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, 
                                private FileUploaderInterface $fileUploader)
    {
    }

    #[Route('/candidat/profile', name: 'candidate_profile')]
    public function index(): Response
    {
        /** @var Candidate $candidate */
        $candidate = $this->getUser();

        return $this->render('candidate/profile.html.twig', [
            'candidate' => $candidate
        ]);
    }

    #[Route('/candidat/parametrage', name: 'candidate_setting')]
    public function setting(Request $request): Response
    {
        /** @var Candidate $candidate */
        $candidate = $this->getUser();
        $form = $this->createForm(UpdateProfileType::class, $candidate);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $cvAttachment = $form->get("cvAttachment")->getData(); 
            if ($cvAttachment) {
               $filename = $this->fileUploader->upload($cvAttachment);
               $candidate->setCvAttachment($filename);
            }

            foreach ($candidate->getProfExperiences() as $profExp) {
                $profExp->setCandidate($candidate);
                $this->entityManager->persist($profExp);
            }

            $this->entityManager->persist($candidate);
            $this->entityManager->flush();

            $this->addFlash("success", "Votre profile a été mis à jour avec succès");

            return $this->redirectToRoute("candidate_profile");
        }

        return $this->render('candidate/setting.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
