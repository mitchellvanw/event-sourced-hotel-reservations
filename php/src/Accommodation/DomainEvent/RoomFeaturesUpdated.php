<?php

namespace Accommodation\DomainEvent;

class RoomFeaturesUpdated
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $roomId,
        private array $features,
        private array $removedFeatures = [],
        private ?string $updatedBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function roomId(): string
    {
        return $this->roomId;
    }

    public function features(): array
    {
        return $this->features;
    }

    public function removedFeatures(): array
    {
        return $this->removedFeatures;
    }

    public function updatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}