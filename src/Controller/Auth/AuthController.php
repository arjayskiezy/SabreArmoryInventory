<?php

namespace App\Controller\Auth;

use App\Repository\RankRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method is handled by Symfony Security.');
    }

    #[Route('/signup', name: 'app_signup')]
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, RankRepository $rankRepository): Response
    {
        if ($request->isMethod('POST')) {
            $full_name = $request->request->get('full_name');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirm_password = $request->request->get('confirm_password');
            $user_rank = $request->request->get('user_rank');

            if ($password !== $confirm_password) {
                $this->addFlash('Error', 'Passwords do not match!');
                return $this->redirectToRoute('app_signup');
            }

            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('Error', 'Email already registered!');
                return $this->redirectToRoute('app_signup');
            }

            $user = new User();
            $user->setFullName($full_name);
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $rankId = $request->request->get('user_rank');
            $user_rank = $rankRepository->find($rankId);   

            if (!$user_rank) {
                $this->addFlash('error', 'Invalid rank selected.');
                return $this->redirectToRoute('app_signup');
            }

            // assign the object to the user
            $user->setUserRank($user_rank);

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('Success', 'Account created successfully! You can now login.');
            return $this->redirectToRoute('app_login');
        }
        $ranks = $rankRepository->findAll();
        return $this->render('Auth/signup/signup.html.twig', ['ranks' => $ranks,]);
    }

    #[Route('/forgotPassword', name: 'app_forgot_password')]
    public function forgotPassword(): Response
    {
        return $this->render('Auth/forgotPassword/forgotPassword.html.twig');
    }
}
