<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserRoleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserRoleController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, 
                                private PaginatorInterface $paginator, 
                                private EntityManagerInterface $entityManager)
    {
    }
    
    #[Route('/admin/utilisateur/role', name: 'admin_user_role_index')]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 10;
        $users = $this->paginator->paginate($this->userRepository->findBy([], ["id" => "desc"]), $page, $limit); 

        return $this->render('admin/user_role/index.html.twig', [
            "users" => $users
        ]);
    }

    #[Route('/admin/utilisateur/role/{id}/editer', name: 'admin_user_role_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash("success", "Le rôle est modifié avec succès");

            return $this->redirectToRoute('admin_user_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user_role/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
