<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\ActivityLogService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class SecuritySubscriber implements EventSubscriberInterface
{
    private ActivityLogService $logService;

    public function __construct(ActivityLogService $logService)
    {
        $this->logService = $logService;
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        // Ensure $user is an instance of your User entity
        if (!$user instanceof User) {
            return;
        }

        $this->logService->log(
            'login',
            $user->getId(),
            "User logged in: {$user->getFullName()}"
        );
    }

    public function onLogout(LogoutEvent $event): void
    {
        $token = $event->getToken();
        if (!$token) {
            return;
        }

        $user = $token->getUser();

        // Ensure $user is an instance of your User entity
        if (!$user instanceof User) {
            return;
        }

        $this->logService->log(
            'logout',
            $user->getId(),
            "User logged out: {$user->getFullName()}"
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            LogoutEvent::class => 'onLogout',
        ];
    }
}
