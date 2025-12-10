<?php

namespace App\Controller\Customer;

use App\Entity\UnitInstance;
use App\Form\UnitReportType;
use App\Repository\UnitInstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
class ReportController extends AbstractController
{
    #[Route('/report', name: 'app_customer_dashboard')]
    #[IsGranted('ROLE_CUSTOMER')]

    public function index(Request $request, EntityManagerInterface $em,): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $userUnits = $user->getUnitInstances()->toArray();
        $form = $this->createForm(UnitReportType::class, null, ['user_units' => $userUnits]);
        $form->handleRequest($request);
        return $this->render('/UserPage/Customer/views/reports/_reports.html.twig', [
            'form' => $form,
            'units' => $userUnits,
        ]);
    }

    #[Route('/report/create', name: 'app_unit_report', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $userUnits = $user->getUnitInstances()->toArray();


        $form = $this->createForm(UnitReportType::class, null, ['user_units' => $userUnits]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UnitInstance $unit */
            $unit = $form->get('unit')->getData();
            $unit->setItemStatus($form->get('itemStatus')->getData());
            $unit->setDescription($form->get('reportNotes')->getData());

            $em->persist($unit);
            $em->flush();

            $this->addFlash('success', 'Your report has been submitted!');
            return $this->redirectToRoute('app_unit_report');
        }

return $this->render('/UserPage/Customer/views/reports/_reports.html.twig', [
            'form' => $form,
            'units' => $userUnits,
        ]);    }
}
