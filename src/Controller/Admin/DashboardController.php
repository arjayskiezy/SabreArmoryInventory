<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Unit;
use App\Entity\UnitInstance;
use App\Entity\ActivityLog;
use App\Entity\UnitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(EntityManagerInterface $em): Response
    {
        $totalUsers = $em->getRepository(User::class)
            ->count(['status' => \App\Enum\UserActiveStatus::ACTIVE]);

        $totalStaff = $em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_STAFF%')
            ->getQuery()
            ->getSingleScalarResult();

        $totalUnitInstances = $em->getRepository(UnitInstance::class)->count([]);
        $totalReports =  $totalUnitInstances;

        $recentLogs = $em->getRepository(ActivityLog::class)
            ->createQueryBuilder('a')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        // Category distribution
        $unitTypes = $em->getRepository(UnitType::class)->findAll();
        $categoryLabels = [];
        $categoryData = [];

        foreach ($unitTypes as $type) {
            $categoryLabels[] = $type->getName();
            $count = $em->getRepository(Unit::class)
                ->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->where('u.type = :type')
                ->setParameter('type', $type)
                ->getQuery()
                ->getSingleScalarResult();
            $categoryData[] = (int) $count;
        }

        $categoryDistribution = [
            'labels' => $categoryLabels,
            'data' => $categoryData,
        ];

        return $this->render('UserPage/Admin/views/dashboard/_dashboard.html.twig', [
            'totalUsers' => $totalUsers,
            'totalStaff' => $totalStaff,
            'totalReports' => $totalReports,
            'recentLogs' => $recentLogs,
            'categoryDistribution' => $categoryDistribution,
        ]);
    }
}
