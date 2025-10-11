<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RequestController extends AbstractController
{
    #[Route('/request', name: 'app_request')]
    public function index(): Response
    {
        return $this->render('/UserPage/Admin/views/requests/_requests.html.twig', [
            'controller_name' => 'RequestController',
        ]);
    }
}
