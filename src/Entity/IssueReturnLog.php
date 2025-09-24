<?php

namespace App\Entity;

use App\Repository\IssueReturnLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IssueReturnLogRepository::class)]
class IssueReturnLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $equipment_id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $issued_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $returned_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipmentId(): ?int
    {
        return $this->equipment_id;
    }

    public function setEquipmentId(int $equipment_id): static
    {
        $this->equipment_id = $equipment_id;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getIssuedAt(): ?\DateTimeImmutable
    {
        return $this->issued_at;
    }

    public function setIssuedAt(\DateTimeImmutable $issued_at): static
    {
        $this->issued_at = $issued_at;

        return $this;
    }

    public function getReturnedAt(): ?\DateTimeImmutable
    {
        return $this->returned_at;
    }

    public function setReturnedAt(\DateTimeImmutable $returned_at): static
    {
        $this->returned_at = $returned_at;

        return $this;
    }
}
