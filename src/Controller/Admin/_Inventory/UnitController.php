<?php

namespace App\Controller\Admin\_Inventory;

use App\Entity\Unit;
use App\Form\UnitType;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/unit')]
final class UnitController extends AbstractController
{
    #[Route(name: 'app_unit_index', methods: ['GET'])]
    public function index(UnitRepository $unitRepository, Request $request): Response
    {
        return $this->render('UserPage/Admin/views/inventory/_inventory.html.twig', [
            'units' => $unitRepository->findAll(),
            'focus' => $request->query->get('focus', null),
        ]);
    }

    #[Route('/new', name: 'app_unit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $unit = new Unit();
        $form = $this->createForm(UnitType::class, $unit, [
            'action' => $this->generateUrl('app_unit_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image_path')->getData();

            if ($imageFile) {
                $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $unit->getName());
                $extension = $imageFile->guessExtension() ?: 'png';
                $newFilename = $safeFilename . '.' . $extension;

                $uploadsDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $imageFile->move($uploadsDir, $newFilename);

                $unit->setImagePath($newFilename);
            }

            $entityManager->persist($unit);
            $entityManager->flush();

            return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
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
    public function edit(Unit $unit, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UnitType::class, $unit, [
            'action' => $this->generateUrl('app_unit_edit', ['id' => $unit->getId()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('UserPage/Admin/forms/unit/edit.html.twig', [
            'unit' => $unit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_unit_delete', methods: ['POST'])]
    public function delete(Request $request, Unit $unit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $unit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($unit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_unit_index', [], Response::HTTP_SEE_OTHER);
    }
}
