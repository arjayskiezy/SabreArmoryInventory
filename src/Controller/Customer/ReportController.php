<?php

namespace App\Controller\Customer;

use App\Entity\UnitInstance;
use App\Form\ReportType;
use App\Entity\Report;
use App\Repository\UnitInstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\ActivityLogService;

#[Route('/user')]
class ReportController extends AbstractController
{
    #[Route('/report', name: 'app_customer_dashboard')]
    #[IsGranted('ROLE_CUSTOMER')]
    public function index(Request $request, EntityManagerInterface $em, ActivityLogService $logService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $userUnits = $user->getUnitInstances()->toArray();

        $report = new Report();
        $form = $this->createForm(ReportType::class, $report, ['user_units' => $userUnits]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $report->setUser($user);
            $report->setCreatedAt(new \DateTimeImmutable());

            $em->persist($report);
            $em->flush();
            $logService->log('create', $report->getId(), "Created report: {$report->getNotes()}");

            $this->addFlash('success', 'Your report has been submitted!');
            return $this->redirectToRoute('app_customer_dashboard');
        }

        return $this->render('UserPage/Customer/views/reports/_reports.html.twig', [
            'form' => $form->createView(),
            'units' => $userUnits,
        ]);
    }
}
