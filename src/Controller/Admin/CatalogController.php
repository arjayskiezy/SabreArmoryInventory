<?php

namespace App\Controller\Admin;

use App\Repository\UnitInstanceRepository;
use App\Repository\UnitTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CatalogController extends AbstractController
{
    #[Route('/catalog', name: 'app_catalog')]
    public function index(
        UnitInstanceRepository $unitInstanceRepository,
        UnitTypeRepository $unitTypeRepository
    ): Response {
        $unitInstances = $unitInstanceRepository->findAll();
        $unitTypes = $unitTypeRepository->findAll(); 

        return $this->render('UserPage/Admin/views/catalog/_catalog.html.twig', [
            'unitInstances' => $unitInstances,
            'unitTypes' => $unitTypes,
        ]);
    }
}
