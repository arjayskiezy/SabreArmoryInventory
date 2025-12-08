<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_setting')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to access settings.');
        }

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newPassword = $form->get('newPassword')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();
            $currentPassword = $form->get('currentPassword')->getData();

            // Only verify password if the user wants to change it
            if ($newPassword) {
                if (!$currentPassword) {
                    $this->addFlash('error', 'You must enter your current password to change it.');
                    return $this->redirectToRoute('app_setting');
                }

                if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                    $this->addFlash('error', 'Current password is incorrect.');
                    return $this->redirectToRoute('app_setting');
                }

                if ($newPassword !== $confirmPassword) {
                    $this->addFlash('error', 'New password and confirmation do not match.');
                    return $this->redirectToRoute('app_setting');
                }

                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
            }


            $em->flush();
            $this->addFlash('success', 'Profile updated successfully.');
            return $this->redirectToRoute('app_setting');
        }

        return $this->render('UserPage/Admin/views/settings/_settings.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
