<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
final class UserRolesController extends AbstractController
{
    #[Route('/user/roles', name: 'app_user_roles_index', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $userRepository->createQueryBuilder('u')->getQuery();

        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->render('UserPage/Admin/views/userRoles/_userRoles.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/roles/new', name: 'app_user_roles_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
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
                $this->addFlash('Error', 'Password is required for new users.');
                return $this->redirectToRoute('app_user_roles_new');
            }

            try {
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('Success', 'User created successfully.');
                return $this->redirectToRoute('app_user_roles_index');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('Error', 'The email address is already in use. Please use another one.');
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
    public function show(User $user): Response
    {
        return $this->render('UserPage/Admin/forms/user_roles/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/roles/{id}/edit', name: 'app_user_roles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
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
                $this->addFlash('Success', 'User updated successfully.');
                return $this->redirectToRoute('app_user_roles_index');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('Error', 'That email address is already in use by another user.');
                return $this->redirectToRoute('app_user_roles_edit', ['id' => $user->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('Error', 'An unexpected error occurred: ' . $e->getMessage());
            }
        }

        return $this->render('UserPage/Admin/forms/user_roles/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/roles/{id}', name: 'app_user_roles_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $token = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $token)) {
            try {
                $entityManager->remove($user);
                $entityManager->flush();
                $this->addFlash('Success', 'User deleted successfully.');
            } catch (\Exception $e) {
                $this->addFlash('Error', 'An error occurred while deleting the user: ' . $e->getMessage());
            }
        } else {
            $this->addFlash('Error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_user_roles_index');
    }
}
