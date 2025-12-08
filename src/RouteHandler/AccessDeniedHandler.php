<?php

namespace App\RouteHandler;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private UrlGeneratorInterface $urlGenerator;
    private Security $security;

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    /**
     * Centralized method to determine dashboard URL based on role
     */
    private function getDashboardRedirect(): string
    {
        $user = $this->security->getUser();

        if (!$user) {
            return $this->urlGenerator->generate('app_login');
        }

        $roles = $user->getRoles();

        if (in_array('ROLE_ADMIN', $roles)) {
            return $this->urlGenerator->generate('app_admin_dashboard');
        } elseif (in_array('ROLE_CUSTOMER', $roles)) {
            return $this->urlGenerator->generate('app_customer_dashboard');
        }

        return $this->urlGenerator->generate('app_login');
    }

    /**
     * Handle access denied exceptions
     */
    public function handle(Request $request, AccessDeniedException $exception): RedirectResponse
    {
        $redirect = $this->getDashboardRedirect();
        return new RedirectResponse($redirect);
    }

    /**
     * Handle 404 exceptions
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            $redirect = $this->getDashboardRedirect();
            $event->setResponse(new RedirectResponse($redirect));
        }
    }
}
