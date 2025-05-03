<?php

namespace Accommodation\DomainEvent;

class RoomBlocked
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $roomId,
        private string $reason,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private ?string $notes = null,
        private ?string $blockedBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function roomId(): string
    {
        return $this->roomId;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function blockedBy(): ?string
    {
        return $this->blockedBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}