<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_setting')]
    public function index(): Response
    {
        return $this->render('/UserPage/Admin/views/settings/_settings.html.twig', [
            'controller_name' => 'SettingsController',
        ]);
    }
}
