<?php

namespace App\Controller\Admin;

use App\Repository\ActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
final class ActivityLogController extends AbstractController
{
    #[Route('/activity-logs', name: 'app_activity_logs', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, ActivityLogRepository $logRepo): Response
    {
        // Filters from query parameters
        $username = $request->query->get('username');
        $action = $request->query->get('action');
        $dateFrom = $request->query->get('date_from');
        $dateTo = $request->query->get('date_to');

        // Fetch logs with optional filtering
        $logs = $logRepo->findByFilters($username, $action, $dateFrom, $dateTo);

        return $this->render('UserPage/Admin/views/activity-logs/_activity-logs.html.twig', [
            'logs' => $logs,
        ]);
    }
}
