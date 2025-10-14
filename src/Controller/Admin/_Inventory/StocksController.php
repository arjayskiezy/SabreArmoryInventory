<?php

namespace App\Controller\Admin\_Inventory;

use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\StockRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/stocks')]
final class StocksController extends AbstractController
{
    #[Route('/', name: 'app_stocks_index', methods: ['GET'])]
    public function index(UnitRepository $unitRepository, StockRepository $stockRepository, Request $request): Response
    {
        return $this->render('UserPage/Admin/views/inventory/_inventory.html.twig', [
            'units' => $unitRepository->findAll(),
            'stocks' => $stockRepository->findAll(),
            'focus' => $request->query->get('focus', null),
        ]);
    }

    #[Route('/new', name: 'app_stocks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock, [
            'action' => $this->generateUrl('app_stocks_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($stock);
            $em->flush();

            $this->addFlash('success', 'Stock added successfully!');
            return $this->redirectToRoute('app_stocks_index');
        }

        return $this->render('UserPage/Admin/forms/stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stocks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stock $stock, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Stock updated successfully!');
            return $this->redirectToRoute('app_stocks_index');
        }

        return $this->render('UserPage/Admin/forms/stock/edit.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stocks_delete', methods: ['POST'])]
    public function delete(Request $request, Stock $stock, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stock->getId(), $request->request->get('_token'))) {
            $em->remove($stock);
            $em->flush();
            $this->addFlash('success', 'Stock deleted successfully!');
        }

        return $this->redirectToRoute('app_stocks_index');
    }

    #[Route('/admin/stock-logs', name: 'app_stock_logs')]
    public function stockLogs(StockRepository $repo): Response
    {
        return $this->render('UserPage/Admin/forms/stock/index.html.twig', [
            'stocks' => $repo->findAll(),
        ]);
    }
}
