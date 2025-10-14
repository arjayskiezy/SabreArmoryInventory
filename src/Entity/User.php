<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string>
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Rank $user_rank = null;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, UnitInstance>
     */
    #[ORM\OneToMany(
        targetEntity: UnitInstance::class,
        mappedBy: 'owner',
        cascade: ['remove'],
        orphanRemoval: true
    )]
    private Collection $unitInstances;

    public function __construct()
    {
        $this->unitInstances = new ArrayCollection();
    }

    // ------------------- GETTERS & SETTERS -------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // Deprecated for Symfony 8
    }

    public function getUserRank(): ?Rank
    {
        return $this->user_rank;
    }

    public function setUserRank(?Rank $user_rank): static
    {
        $this->user_rank = $user_rank;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    // ------------------- LIFECYCLE CALLBACKS -------------------

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, UnitInstance>
     */
    public function getUnitInstances(): Collection
    {
        return $this->unitInstances;
    }

    public function addUnitInstance(UnitInstance $unitInstance): static
    {
        if (!$this->unitInstances->contains($unitInstance)) {
            $this->unitInstances->add($unitInstance);
            $unitInstance->setOwner($this);
        }

        return $this;
    }

    public function removeUnitInstance(UnitInstance $unitInstance): static
    {
        if ($this->unitInstances->removeElement($unitInstance)) {
            // set the owning side to null (unless already changed)
            if ($unitInstance->getOwner() === $this) {
                $unitInstance->setOwner(null);
            }
        }

        return $this;
    }
}
