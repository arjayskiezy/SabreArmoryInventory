<?php

namespace App\Controller\Admin;

use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class ReportController extends AbstractController
{
    #[Route('/reports', name: 'app_admin_reports')]
    public function index(ReportRepository $reportRepo): Response
    {
        $reports = $reportRepo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('UserPage/Admin/views/reports/_reports.html.twig', [
            'reports' => $reports,
        ]);
    }
}
