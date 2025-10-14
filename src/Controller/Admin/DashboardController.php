<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('UserPage/Admin/views/dashboard/_dashboard.html.twig');
    }

    #[Route('/admin/dashboard/data', name: 'app_admin_dashboard_data')]
    public function data(): JsonResponse
    {
        return new JsonResponse([
            'issuanceTrends' => [
                'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                'data' => [10, 25, 18, 32],
            ],
            'categoryDistribution' => [
                'labels' => ['Firearms', 'Gear', 'Ammunition'],
                'data' => [45, 35, 20],
            ],
        ]);
    }
}
