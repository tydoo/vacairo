<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_UUID', fields: ['uuid'])]
class User implements UserInterface {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $uuid = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(nullable: false, options: ['default' => false])]
    private ?bool $onboarded = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastLoggedAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateDebutFormationS1S2 = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateFinFormationS1S2 = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateDebutFormationS3S4 = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateFinFormationS3S4 = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateDebutContrat = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getUuid(): ?string {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static {
        $this->roles = $roles;

        return $this;
    }

    public function isOnboarded(): ?bool {
        return $this->onboarded;
    }

    public function setOnboarded(bool $onboarded): static {
        $this->onboarded = $onboarded;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastLoggedAt(): ?\DateTimeImmutable {
        return $this->lastLoggedAt;
    }

    public function setLastLoggedAt(\DateTimeImmutable $lastLoggedAt): static {
        $this->lastLoggedAt = $lastLoggedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void {
        $dateTime = new \DateTimeImmutable();
        if (!$this->createdAt) {
            $this->createdAt = $dateTime;
        }
        if (!$this->updatedAt) {
            $this->updatedAt = $dateTime;
        }

        if (!$this->lastLoggedAt) {
            $this->lastLoggedAt = $dateTime;
        }
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getDateDebutFormationS1S2(): ?\DateTimeImmutable
    {
        return $this->dateDebutFormationS1S2;
    }

    public function setDateDebutFormationS1S2(?\DateTimeImmutable $dateDebutFormationS1S2): static
    {
        $this->dateDebutFormationS1S2 = $dateDebutFormationS1S2;

        return $this;
    }

    public function getDateFinFormationS1S2(): ?\DateTimeImmutable
    {
        return $this->dateFinFormationS1S2;
    }

    public function setDateFinFormationS1S2(?\DateTimeImmutable $dateFinFormationS1S2): static
    {
        $this->dateFinFormationS1S2 = $dateFinFormationS1S2;

        return $this;
    }

    public function getDateDebutFormationS3S4(): ?\DateTimeImmutable
    {
        return $this->dateDebutFormationS3S4;
    }

    public function setDateDebutFormationS3S4(?\DateTimeImmutable $dateDebutFormationS3S4): static
    {
        $this->dateDebutFormationS3S4 = $dateDebutFormationS3S4;

        return $this;
    }

    public function getDateFinFormationS3S4(): ?\DateTimeImmutable
    {
        return $this->dateFinFormationS3S4;
    }

    public function setDateFinFormationS3S4(?\DateTimeImmutable $dateFinFormationS3S4): static
    {
        $this->dateFinFormationS3S4 = $dateFinFormationS3S4;

        return $this;
    }

    public function getDateDebutContrat(): ?\DateTimeImmutable
    {
        return $this->dateDebutContrat;
    }

    public function setDateDebutContrat(?\DateTimeImmutable $dateDebutContrat): static
    {
        $this->dateDebutContrat = $dateDebutContrat;

        return $this;
    }
}
