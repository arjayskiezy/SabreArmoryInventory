<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InventoryController extends AbstractController
{
    #[Route('/inventory', name: 'app_inventory')]
    public function index(): Response
    {
        return $this->render('UserPage/Admin/views/inventory/_inventory.html.twig', [
            'controller_name' => 'InventoryController',
        ]);
    }
}
