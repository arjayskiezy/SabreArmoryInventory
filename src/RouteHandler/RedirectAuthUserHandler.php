<?php

namespace App\RouteHandler;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectAuthUserHandler implements EventSubscriberInterface
{
    private UrlGeneratorInterface $urlGenerator;
    private Security $security;

    /**
     * Guest pages to protect from logged-in users
     */
    private array $guestPaths = [
        '/',
        '/login',
        '/signup',
        '/forgotPassword',
        '/contacts'
    ];

    public function __construct(UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InteractiveLoginEvent::class => 'onLogin',
            'kernel.request' => 'onKernelRequest',
        ];
    }

    /**
     * Redirect users immediately after login based on their role
     */
    public function onLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (in_array('ROLE_CUSTOMER', $user->getRoles())) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_customer_dashboard'));
        } elseif (in_array('ROLE_ADMIN', $user->getRoles())) {
            $response = new RedirectResponse($this->urlGenerator->generate('app_admin_dashboard'));
        } else {
            $response = new RedirectResponse($this->urlGenerator->generate('app_inventory'));
        }

        if (isset($response)) {
            // Store target URL in session for Symfony to redirect after login
            $event->getRequest()->getSession()->set('redirect_after_login', $response->getTargetUrl());
        }
    }

    /**
     * Prevent logged-in users from accessing guest pages
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();
        $user = $this->security->getUser();

        // Only act if user is logged in and trying to access a guest page
        if ($user && $user !== 'anon.' && in_array($path, $this->guestPaths, true)) {
            if (in_array('ROLE_CUSTOMER', $user->getRoles())) {
                $redirect = $this->urlGenerator->generate('app_customer_dashboard');
            } elseif (in_array('ROLE_ADMIN', $user->getRoles())) {
                $redirect = $this->urlGenerator->generate('app_admin_dashboard');
            } else {
                $redirect = $this->urlGenerator->generate('app_inventory');
            }

            $event->setResponse(new RedirectResponse($redirect));
        }
    }
}
