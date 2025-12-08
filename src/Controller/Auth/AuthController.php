<?php

namespace App\Controller\Auth;

use App\Form\UserSignupType;
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
    public function signup(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        $user = new User();
        $form = $this->createForm(UserSignupType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $form->get('password')->getData();
            $confirm = $form->get('confirmPassword')->getData();
            $email = $form->get('email')->getData();

            $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($existingUser) {
                $this->addFlash('error', 'Email already registered!');
                return $this->redirectToRoute('app_signup');
            }

            if ($password !== $confirm) {
                $this->addFlash('error', 'Passwords do not match!');
                return $this->redirectToRoute('app_signup');
            }

            $user->setRoles(['ROLE_USER', 'ROLE_CUSTOMER']);

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Account created successfully! You can now login.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('Auth/signup/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forgotPassword', name: 'app_forgot_password')]
    public function forgotPassword(): Response
    {
        return $this->render('Auth/forgotPassword/forgotPassword.html.twig');
    }
}
