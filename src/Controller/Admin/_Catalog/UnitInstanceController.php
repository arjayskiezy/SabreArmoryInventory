<?php

namespace App\Controller\Admin\_Catalog;

use App\Entity\UnitInstance;
use App\Form\UnitInstanceType;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class UnitInstanceController extends AbstractController
{
    private ActivityLogService $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    #[Route('/unit/instance', name: 'app_unit_instance_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->redirectToRoute('app_catalog');
    }

    #[Route('/new', name: 'app_unit_instance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ActivityLogService $logService): Response
    {
        $unitInstances = new UnitInstance();
        $form = $this->createForm(UnitInstanceType::class, $unitInstances);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($unitInstances);
            $entityManager->flush();

            $this->addFlash('success', 'Unit instance created successfully.');
            $logService->log('create', $unitInstances->getId(), "Created unit instance: {$unitInstances->getSerialNumber()}");

            return $this->redirectToRoute('app_unit_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('UserPage/Admin/forms/unit_instance/new.html.twig', [
            'unit_instance' => $unitInstances,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_unit_instance_show', methods: ['GET'])]
    public function show(UnitInstance $unitInstance): Response
    {
        return $this->render('UserPage/Admin/forms/unit_instance/show.html.twig', [
            'unit_instance' => $unitInstance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_unit_instance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UnitInstance $unitInstances, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(UnitInstanceType::class, $unitInstances);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Unit instance updated successfully.');
            $this->logService->log('update', $unitInstances->getId(), "Updated unit instance: {$unitInstances->getSerialNumber()}");

            return $this->redirectToRoute('app_unit_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        $weapons = $entityManager->getRepository(\App\Entity\Unit::class)->findBy([], ['name' => 'ASC']);
        $users = $entityManager->getRepository(\App\Entity\User::class)->findBy([], ['full_name' => 'ASC']);

        return $this->render('UserPage/Admin/forms/unit_instance/edit.html.twig', [
            'unit_instance' => $unitInstances,
            'weapons' => $weapons,
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_unit_instance_delete', methods: ['POST'])]
    public function delete(Request $request, UnitInstance $unitInstance, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $unitInstance->getId(), $token)) {
            $entityManager->remove($unitInstance);
            $entityManager->flush();

            $this->addFlash('success', 'Unit instance deleted successfully.');
            $this->logService->log('delete',  $unitInstance->getId(), "Deleted unit instance: {$unitInstance->getSerialNumber()}");
        } else {
            $this->addFlash('error', 'Invalid CSRF token. Delete failed.');
        }

        return $this->redirectToRoute('app_unit_instance_index', [], Response::HTTP_SEE_OTHER);
    }
}
