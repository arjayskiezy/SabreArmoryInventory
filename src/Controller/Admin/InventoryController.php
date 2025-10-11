<?php

namespace App\Controller\Admin;

use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class InventoryController extends AbstractController
{
    #[Route('/inventory', name: 'app_inventory')]
    public function index(UnitRepository $unitRepo): Response
    {
        $units = $unitRepo->findAll();
        return $this->render('UserPage/Admin/views/inventory/_inventory.html.twig', [
            'units' => $units
        ]);
    }
}
