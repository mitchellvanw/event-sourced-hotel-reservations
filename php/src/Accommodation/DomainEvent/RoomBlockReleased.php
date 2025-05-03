<?php

namespace Accommodation\DomainEvent;

class RoomBlockReleased
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $roomId,
        private string $reason,
        private ?string $releasedBy = null,
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

    public function releasedBy(): ?string
    {
        return $this->releasedBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}