<?php

namespace App\RouteHandler;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class NotFoundHandler
{
    private UrlGeneratorInterface $urlGenerator;
    private Security $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            $user = $this->security->getUser();

            // If logged in, redirect based on role
            if ($user) {
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    $redirect = $this->urlGenerator->generate('app_admin_dashboard');
                } elseif (in_array('ROLE_CUSTOMER', $user->getRoles())) {
                    $redirect = $this->urlGenerator->generate('app_customer_dashboard');
                } else {
                    $redirect = $this->urlGenerator->generate('app_login');
                }
            } else {
                // Not logged in, redirect to login
                $redirect = $this->urlGenerator->generate('app_login');
            }

            $event->setResponse(new RedirectResponse($redirect));
        }
    }
}
