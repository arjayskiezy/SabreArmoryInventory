<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestController extends AbstractController
{
    #[Route('/request', name: 'app_request')]
    public function index(): Response
    {
        return $this->render('/admin/pages/request/request.html.twig', [
            'controller_name' => 'RequestController',
        ]);
    }
}
