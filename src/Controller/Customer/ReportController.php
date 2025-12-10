<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
final class ReportController extends AbstractController
{
    #[Route('/report', name: 'app_customer_dashboard')]
    #[IsGranted('ROLE_CUSTOMER')]

    public function index(): Response
    {
        return $this->render('/UserPage/Customer/views/reports/_reports.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }
}
