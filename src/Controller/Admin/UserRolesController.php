<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/user/roles')]
final class UserRolesController extends AbstractController
{
    #[Route(name: 'app_user_roles_index', methods: ['GET'])]
    public function index(
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // Option 1: Use QueryBuilder for pagination efficiency
        $query = $userRepository->createQueryBuilder('u')->getQuery();

        // Option 2: Or paginate an array (works but not as efficient)
        // $query = $userRepository->findAll();

        $users = $paginator->paginate(
            $query, // query or array
            $request->query->getInt('page', 1), // page number
            10 // items per page
        );

        return $this->render('UserPage/Admin/views/userRoles/_userRoles.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'app_user_roles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_roles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('UserPage/Admin/forms/user_roles/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_roles_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('UserPage/Admin/forms/user_roles/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_roles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_roles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('UserPage/Admin/forms/user_roles/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_roles_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_roles_index', [], Response::HTTP_SEE_OTHER);
    }
}
