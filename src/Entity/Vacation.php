<?php

namespace App\Entity;

use App\Repository\VacationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VacationRepository::class)]
class Vacation {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255, options: ['default' => 'draft'])]
    private ?string $state = 'draft';

    #[ORM\ManyToOne(inversedBy: 'vacations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VacationType $type = null;

    #[ORM\Column]
    private ?int $hours = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static {
        $this->date = $date;

        return $this;
    }

    public function getState(): ?string {
        return $this->state;
    }

    public function setState(string $state): static {
        $this->state = $state;

        return $this;
    }

    public function getType(): ?VacationType {
        return $this->type;
    }

    public function setType(?VacationType $type): static {
        $this->type = $type;

        return $this;
    }

    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(int $hours): static
    {
        $this->hours = $hours;

        return $this;
    }
}
