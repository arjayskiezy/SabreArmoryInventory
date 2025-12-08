<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route( name: 'app_customer_dashboard')]
    public function index(): Response
    {
        
        return $this->render('/UserPage/Customer/user.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
