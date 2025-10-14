<?php

namespace App\Controller\Admin;

use App\Repository\UnitRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class InventoryController extends AbstractController
{
    #[Route('/inventory', name: 'app_inventory')]
    public function index(UnitRepository $unitRepository, StockRepository $stockRepository, Request $request): Response
    {
        return $this->render('UserPage/Admin/views/inventory/_inventory.html.twig', [
            'units' => $unitRepository->findAll(),
            'stocks' => $stockRepository->findAll(),
            'focus' => $request->query->get('focus', null),
        ]);
    }
}
