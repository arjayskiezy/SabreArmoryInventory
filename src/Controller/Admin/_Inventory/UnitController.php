<?php

namespace App\Controller\Admin\_Inventory;

use App\Entity\Unit;
use App\Form\UnitType;
use App\Repository\UnitRepository;
use App\Repository\StockRepository;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/unit')]
final class UnitController extends AbstractController
{
    #[Route('/index',name: 'app_unit_index', methods: ['GET'])]
    public function index(UnitRepository $unitRepository, StockRepository $stockRepository, Request $request): Response
    {
        return $this->redirectToRoute('app_inventory');
    }

    #[Route('/new', name: 'app_unit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ActivityLogService $logService): Response
    {
        $unit = new Unit();
        $form = $this->createForm(UnitType::class, $unit, [
            'action' => $this->generateUrl('app_unit_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $imageFile = $form->get('image_path')->getData();

                if ($imageFile) {
                    $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $unit->getName());
                    $extension = $imageFile->guessExtension() ?: 'png';
                    $newFilename = $safeFilename . '.' . $extension;

                    $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                    if (!is_dir($uploadsDir)) {
                        mkdir($uploadsDir, 0755, true);
                    }

                    $imageFile->move($uploadsDir, $newFilename);
                    $unit->setImagePath($newFilename);
                }

                $entityManager->persist($unit);
                $entityManager->flush();

                $logService->log('create', $unit->getId(), "Created unit: {$unit->getName()}");


                $this->addFlash('success', 'Unit created successfully.');
                return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while creating the unit: ' . $e->getMessage());
            }
        }

        return $this->render('UserPage/Admin/forms/unit/new.html.twig', [
            'unit' => $unit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_unit_show', methods: ['GET'])]
    public function show(Unit $unit): Response
    {
        return $this->render('UserPage/Admin/forms/unit/show.html.twig', [
            'unit' => $unit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_unit_edit', methods: ['GET', 'POST'])]
    public function edit(Unit $unit, Request $request, EntityManagerInterface $entityManager, ActivityLogService $logService): Response
    {
        $form = $this->createForm(UnitType::class, $unit, [
            'action' => $this->generateUrl('app_unit_edit', ['id' => $unit->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $imageFile = $form->get('image_path')->getData();

                if ($imageFile) {
                    $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $unit->getName());
                    $extension = $imageFile->guessExtension() ?: 'png';
                    $newFilename = $safeFilename . '.' . $extension;

                    $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                    if (!is_dir($uploadsDir)) {
                        mkdir($uploadsDir, 0755, true);
                    }

                    $imageFile->move($uploadsDir, $newFilename);
                    $unit->setImagePath($newFilename);
                }

                $entityManager->flush();
                $logService->log('update', $unit->getId(), "Updated unit: {$unit->getName()}");

                $this->addFlash('success', 'Unit updated successfully.');
                return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while updating the unit: ' . $e->getMessage());
            }
        }

        return $this->render('UserPage/Admin/forms/unit/edit.html.twig', [
            'unit' => $unit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_unit_delete', methods: ['POST'])]
    public function delete(Request $request, Unit $unit, EntityManagerInterface $entityManager, ActivityLogService $logService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $unit->getId(), $request->request->get('_token'))) {
            try {
                $entityManager->remove($unit);
                $entityManager->flush();
                $logService->log('delete', $unit->getId(), "Deleted unit: {$unit->getName()}");

                $this->addFlash('success', 'Unit deleted successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while deleting the unit: ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_unit_index');
    }
}
