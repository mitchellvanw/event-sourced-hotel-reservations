<?php

namespace Accommodation\DomainEvent;

class RoomCreated
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $roomId,
        private string $roomNumber,
        private string $type,
        private float $rate,
        private string $status,
        private array $features = [],
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function roomId(): string
    {
        return $this->roomId;
    }

    public function roomNumber(): string
    {
        return $this->roomNumber;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function rate(): float
    {
        return $this->rate;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function features(): array
    {
        return $this->features;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}