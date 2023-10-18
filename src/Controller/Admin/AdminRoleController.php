<?php

namespace App\Controller\Admin;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/role')]
class AdminRoleController extends AbstractController
{
    public function __construct(private RoleRepository $roleRepository, private PaginatorInterface $paginator)
    {
    }

    #[Route('/', name: 'admin_role_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 10;
        $roles = $this->paginator->paginate($this->roleRepository->findBy([], ["name" => "desc"]), $page, $limit); 

        return $this->render('admin/role/index.html.twig', [
            'roles' => $roles,
        ]);
    }

    #[Route('/ajout', name: 'admin_role_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($role);
            $entityManager->flush();

            $this->addFlash("success", "Le rôle est ajouté avec succès");

            return $this->redirectToRoute('admin_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/role/new.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editer', name: 'admin_role_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("success", "Le rôle est modifié avec succès");

            return $this->redirectToRoute('admin_role_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/role/edit.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_role_delete', methods: ['POST'])]
    public function delete(Request $request, Role $role, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $entityManager->remove($role);
            $entityManager->flush();
        }

        $this->addFlash("success", "Le rôle est supprimé avec succès");

        return $this->redirectToRoute('admin_role_index', [], Response::HTTP_SEE_OTHER);
    }
}
