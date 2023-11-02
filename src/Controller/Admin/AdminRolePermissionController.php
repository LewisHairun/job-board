<?php

namespace App\Controller\Admin;

use App\Entity\EntityRolePermission;
use App\Entity\Role;
use App\Form\RolePermissionType;
use App\Repository\EntityRolePermissionRepository;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminRolePermissionController extends AbstractController
{
    public function __construct(private RoleRepository $roleRepository, 
                                private PaginatorInterface $paginator, 
                                private EntityManagerInterface $entityManager, 
                                private EntityRolePermissionRepository $entityRolePermissionRepository)
    {
    }
    
    #[Route('/admin/role/permissions', name: 'admin_role_permission_index')]
    public function index(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 10;
        $roles = $this->paginator->paginate($this->roleRepository->findBy([], ["name" => "asc"]), $page, $limit); 

        return $this->render('admin/role_permission/index.html.twig', [
            "roles" => $roles
        ]);
    }

    #[Route('/admin/role/permission/{id}/editer', name: 'admin_role_permission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role): Response
    {
        $form = $this->createForm(RolePermissionType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash("success", "La permission à un rôle est modifié avec succès");

            return $this->redirectToRoute('admin_role_permission_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/role_permission/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/role/permission', name: 'admin_role_permission_list', methods: ['GET', 'POST'])]
    public function manage()
    {
        $entities = scandir(dirname(__DIR__, 2) . "/Entity");
        $entities = array_diff($entities, [".", "..", ".gitignore", "EntityRolePermission.php"]);
        $entitiesData = [];

        $roles = $this->roleRepository->getRoleNames();
        foreach ($entities as $entity) {
            $entityName = substr($entity, 0, strrpos($entity, "."));

            foreach ($roles as $roleName) {
                $entityRolePermission = $this->entityRolePermissionRepository->findOneBy(["entityName" => $entityName, "roleName" => $roleName]) ?? new EntityRolePermission();
                $entityRolePermission->setEntityName($entityName);
                $entityRolePermission->setRoleName($roleName);
                $entitiesData[$entityName][$roleName] = $entityRolePermission;
            }
        }

        $data = [
            "entitiesData" => $entitiesData,
            "roles" => $roles,
        ];

        return $this->render("admin/role_permission/list.html.twig", [
            "data" => $data,
        ]);
    }

    #[Route('/admin/role/permission/save', name: 'admin_role_permission_save', methods: ['POST'])]
    public function save(Request $request)
    {
        $permissions = $request->request->all()["permission"] ?? [];

        $roles = $this->roleRepository->getRoleNames();
        $this->entityRolePermissionRepository->reset();

        foreach ($permissions as $entityName => $role) {
            foreach ($role as $roleName => $methods) {
                $entity = $this->entityRolePermissionRepository->findOneBy(["entityName" => $entityName, "roleName" => $roleName]) ?? new EntityRolePermission();
                $entity->setEntityName($entityName);
                $entity->setRoleName($roleName);
                $methodsChecked = array_keys($methods);

                foreach ($methodsChecked as $method) {
                    $entity->$method(true);
                    $this->entityManager->persist($entity);
                }
            }
        }

        $this->entityManager->flush();

        $this->addFlash("success", "La permission des rôles associées à des entités est modifié avec succès");

        return $this->redirectToRoute("admin_role_permission_list");
    }
}
