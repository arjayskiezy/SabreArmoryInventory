<?php

namespace App\Controller\Admin\_Catalog;

use App\Entity\UnitInstance;
use App\Form\UnitInstanceType;
use App\Repository\UnitInstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/unit/instance')]
final class UnitInstanceController extends AbstractController
{
    #[Route(name: 'app_unit_instance_index', methods: ['GET'])]
    public function index(UnitInstanceRepository $unitInstanceRepository): Response
    {
        return $this->render('unit_instance/index.html.twig', [
            'unit_instances' => $unitInstanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_unit_instance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $unitInstance = new UnitInstance();
        $form = $this->createForm(UnitInstanceType::class, $unitInstance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($unitInstance);
            $entityManager->flush();

            return $this->redirectToRoute('app_unit_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('unit_instance/new.html.twig', [
            'unit_instance' => $unitInstance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_unit_instance_show', methods: ['GET'])]
    public function show(UnitInstance $unitInstance): Response
    {
        return $this->render('unit_instance/show.html.twig', [
            'unit_instance' => $unitInstance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_unit_instance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UnitInstance $unitInstance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UnitInstanceType::class, $unitInstance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_unit_instance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('unit_instance/edit.html.twig', [
            'unit_instance' => $unitInstance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_unit_instance_delete', methods: ['POST'])]
    public function delete(Request $request, UnitInstance $unitInstance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$unitInstance->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($unitInstance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_unit_instance_index', [], Response::HTTP_SEE_OTHER);
    }
}
