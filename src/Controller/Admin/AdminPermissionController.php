<?php

namespace App\Controller\Admin;

use App\Entity\Permission;
use App\Form\PermissionType;
use App\Repository\PermissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/permission')]
class AdminPermissionController extends AbstractController
{
    public function __construct(private PermissionRepository $permissionRepository, private PaginatorInterface $paginator)
    {
    }

    #[Route('/', name: 'admin_permission_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 10;
        $permissions = $this->paginator->paginate($this->permissionRepository->findBy([], ["name" => "desc"]), $page, $limit); 

        return $this->render('admin/permission/index.html.twig', [
            'permissions' => $permissions,
        ]);
    }

    #[Route('/ajout', name: 'admin_permission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $permission = new Permission();
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($permission);
            $entityManager->flush();

            $this->addFlash("success", "La permission est ajouté avec succès");

            return $this->redirectToRoute('admin_permission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/permission/new.html.twig', [
            'permission' => $permission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editer', name: 'admin_permission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Permission $permission, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PermissionType::class, $permission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash("success", "La permission est modifié avec succès");

            return $this->redirectToRoute('admin_permission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/permission/edit.html.twig', [
            'permission' => $permission,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_permission_delete', methods: ['POST'])]
    public function delete(Request $request, Permission $permission, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$permission->getId(), $request->request->get('_token'))) {
            $entityManager->remove($permission);
            $entityManager->flush();
        }

        $this->addFlash("success", "La permission est supprimé avec succès");

        return $this->redirectToRoute('admin_permission_index', [], Response::HTTP_SEE_OTHER);
    }
}
