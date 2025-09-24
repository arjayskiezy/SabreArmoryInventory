<?php

namespace App\Entity;

use App\Enum\RequestStatus;
use App\Repository\RequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $user_id = null;

    #[ORM\Column]
    private ?int $equipment_id = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: RequestStatus::class)]
    private array $status = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $requested_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $approved_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEquipmentId(): ?int
    {
        return $this->equipment_id;
    }

    public function setEquipmentId(int $equipment_id): static
    {
        $this->equipment_id = $equipment_id;

        return $this;
    }

    /**
     * @return RequestStatus[]
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    public function setStatus(array $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRequestedAt(): ?\DateTimeImmutable
    {
        return $this->requested_at;
    }

    public function setRequestedAt(\DateTimeImmutable $requested_at): static
    {
        $this->requested_at = $requested_at;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeImmutable
    {
        return $this->approved_at;
    }

    public function setApprovedAt(?\DateTimeImmutable $approved_at): static
    {
        $this->approved_at = $approved_at;

        return $this;
    }
}
