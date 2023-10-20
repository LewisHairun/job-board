<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\JobOfferRepository;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    public function __construct(private JobOfferRepository $jobOfferRepository, 
                                private UserRepository $userRepository
                            )
    {
    }

    #[Route('/admin/tableau-de-bord', name: 'admin_dashboard')]
    public function dashboard(Request $request, RoleRepository $roleRepository): Response
    {
        $roles = $this->getUser()->getRoles();
        $roles = array_diff($roles, ["ROLE_USER"]);
        $pageAuthorized = [];

        if (count($roles)) {
            $roleName = $roles[0];
            $role = $roleRepository->findOneBy(["name" => $roleName]);
            
            if (count($role->getPermission())) {
                $permissions = $role->getPermission()->toArray();
                
                foreach ($permissions as $permission) {
                    $pageAuthorized[] = $permission->getName();
                }
            }
        }
        
        dd($pageAuthorized);

        $startDate = $request->query->get("start_date") ? new \DateTime($request->query->get("start_date")) : null;
        $endDate = $request->query->get("end_date") ? new \DateTime($request->query->get("end_date")) : null;

        $countJobOffer = $this->jobOfferRepository->countOfferPublished($startDate, $endDate);
        $countCandidacy = $this->userRepository->countCandidacy($startDate, $endDate);

        if (isset($startDate) && isset($endDate)) {
            $countJobOffer = $this->jobOfferRepository->countOfferPublished($startDate, $endDate);
            $countCandidacy = $this->userRepository->countCandidacy($startDate, $endDate);
        } 

        return $this->render('admin/dashboard.html.twig', [
            "countJobOffer" => $countJobOffer,
            "countCandidacy" => $countCandidacy
        ]);
    }
}
