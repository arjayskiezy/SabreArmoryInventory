<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuditController extends AbstractController
{
    #[Route('/audit', name: 'app_audit')]
    public function index(): Response
    {
        return $this->render('/UserPage/Admin/views/audits/_audits.html.twig', [
            'controller_name' => 'AuditController',
        ]);
    }
}
