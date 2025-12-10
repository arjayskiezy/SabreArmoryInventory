<?php

namespace App\Entity;

use App\Enum\ItemStatus;
use App\Enum\UnitStatus;
use App\Repository\UnitInstanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitInstanceRepository::class)]
class UnitInstance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'unitInstances')]
    private ?Unit $weaponType = null;

    #[ORM\Column(length: 10)]
    private ?string $serialNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $purchasedDate = null;

    #[ORM\Column(enumType: UnitStatus::class)]
    private ?UnitStatus $status = UnitStatus::ACTIVE;

    #[ORM\Column(enumType: ItemStatus::class)]
    private ?ItemStatus $itemStatus = ItemStatus::GOOD;

    #[ORM\ManyToOne(inversedBy: 'unitInstances')]
    private ?User $owner = null;

    #[ORM\ManyToOne]
    private ?Unit $unitType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeaponType(): ?Unit
    {
        return $this->weaponType;
    }

    public function setWeaponType(?Unit $weaponType): static
    {
        $this->weaponType = $weaponType;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getPurchasedDate(): ?\DateTimeImmutable
    {
        return $this->purchasedDate;
    }

    public function setPurchasedDate(?\DateTimeImmutable $purchasedDate): static
    {
        $this->purchasedDate = $purchasedDate;

        return $this;
    }

    public function getStatus(): ?UnitStatus
    {
        return $this->status;
    }

    public function setStatus(UnitStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getItemStatus(): ?ItemStatus
    {
        return $this->itemStatus;
    }

    public function setItemStatus(ItemStatus $itemStatus): static
    {
        $this->itemStatus = $itemStatus;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getUnitType(): ?Unit
    {
        return $this->unitType;
    }

    public function setUnitType(?Unit $unitType): static
    {
        $this->unitType = $unitType;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
