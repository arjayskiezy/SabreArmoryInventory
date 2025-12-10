<?php

namespace App\Entity;

use App\Enum\ItemStatus;
use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?UnitInstance $unit = null;

    #[ORM\Column(nullable: true, enumType: ItemStatus::class)]
    private ?ItemStatus $itemStatus = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getUnit(): ?UnitInstance
    {
        return $this->unit;
    }

    public function setUnit(?UnitInstance $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    public function getItemStatus(): ?ItemStatus
    {
        return $this->itemStatus;
    }

    public function setItemStatus(?ItemStatus $itemStatus): static
    {
        $this->itemStatus = $itemStatus;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}
