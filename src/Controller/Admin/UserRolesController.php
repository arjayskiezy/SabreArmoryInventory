<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\UserActiveStatus;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ActivityLogService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[Route('/admin')]
final class UserRolesController extends AbstractController
{
    #[Route('/user/roles', name: 'app_user_roles_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('UserPage/Admin/views/userRoles/_userRoles.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/user/roles/new', name: 'app_user_roles_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ActivityLogService $logService
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('app_user_roles_new'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashed = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashed);
            } else {
                $this->addFlash('error', 'Password is required for new users.');
                return $this->redirectToRoute('app_user_roles_new');
            }

            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $logService->log('create', $user->getId(), "Created user: {$user->getFullName()}");

                $this->addFlash('success', 'User created successfully.');
                return $this->redirectToRoute('app_user_roles_index');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'The email address is already in use. Please use another one.');
                return $this->redirectToRoute('app_user_roles_index');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An unexpected error occurred: ' . $e->getMessage());
            }
        }

        return $this->render('UserPage/Admin/forms/user_roles/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/roles/{id}', name: 'app_user_roles_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(User $user): Response
    {
        return $this->render('UserPage/Admin/forms/user_roles/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/roles/{id}/edit', name: 'app_user_roles_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        ActivityLogService $logService
    ): Response {
        $form = $this->createForm(UserType::class, $user, [
            'action' => $this->generateUrl('app_user_roles_edit', ['id' => $user->getId()]),
            'method' => 'POST',
            'is_edit' => $user->getId() !== null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plain = $form->has('plainPassword') ? $form->get('plainPassword')->getData() : null;

            if ($plain) {
                $hashed = $passwordHasher->hashPassword($user, $plain);
                $user->setPassword($hashed);
            }
            try {
                $entityManager->flush();
                $logService->log('update', $user->getId(), "Updated user: {$user->getFullName()}");

                $this->addFlash('success', 'User updated successfully.');
                return $this->redirectToRoute('app_user_roles_index');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'That email address is already in use by another user.');
                return $this->redirectToRoute('app_user_roles_edit', ['id' => $user->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'An unexpected error occurred: ' . $e->getMessage());
            }
        }

        return $this->render('UserPage/Admin/forms/user_roles/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/roles/{id}', name: 'app_user_roles_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    
    public function delete(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        ActivityLogService $logService
    ): Response {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $token)) {
            try {
                $entityManager->remove($user);
                $entityManager->flush();
                $logService->log('delete', $user->getId(), "Deleted user: {$user->getFullName()}");

                $this->addFlash('success', 'User deleted successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'An error occurred while deleting the user: ' . $e->getMessage());
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_user_roles_index');
    }

    #[Route('/user/roles/{id}/archive', name: 'app_user_roles_archive', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]

    public function archive(Request $request, User $user, EntityManagerInterface $entityManager, ActivityLogService $logService): Response
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('archive' . $user->getId(), $token)) {
            $user->setStatus(UserActiveStatus::INACTIVE);
            $entityManager->flush();
            $logService->log('archive', $user->getId(), "Archived user: {$user->getFullName()}");

            $this->addFlash('success', 'User archived successfully.');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_user_roles_index');
    }
}
