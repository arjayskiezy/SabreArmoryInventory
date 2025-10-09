<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserRolesController extends AbstractController
{
    #[Route('/user/roles', name: 'app_user_roles')]
    public function index(): Response
    {
        return $this->render('UserPage/Admin/views/userRoles/_userRoles.html.twig', [
            'controller_name' => 'UserRolesController',
        ]);
    }
}
