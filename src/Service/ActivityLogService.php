<?php

namespace App\Service;

use App\Entity\ActivityLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ActivityLogService
{
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * Log an activity
     *
     * @param string $action   The performed action (e.g., 'create', 'update', 'delete')
     * @param int|string $targetId  The target entity ID (e.g., Unit ID, Product ID)
     * @param string|null $targetData Optional descriptive data about the target
     */
    public function log(string $action, $targetId, ?string $targetData = null): void
    {
        $user = $this->security->getUser();

        $log = new ActivityLog();
        $log->setAction($action);
        $log->setTargetData($targetData ?? (string)$targetId);

        if ($user instanceof \App\Entity\User) {
            $log->setUserId($user);
            $log->setUsername($user->getFullName() ?? $user->getUserIdentifier());
            $roles = $user->getRoles();
            $log->setRole(implode(', ', $roles));
        } else {
            $log->setUsername('anonymous');
            $log->setRole('ROLE_ANONYMOUS');
        }

        $log->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($log);
        $this->em->flush();
    }
}
