<?php

namespace Accommodation\DomainEvent;

class RoomDiscountApplied
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $discountId,
        private string $roomTypeId,
        private float $discountPercentage,
        private string $discountName,
        private string $discountCode,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private ?string $appliedBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function discountId(): string
    {
        return $this->discountId;
    }

    public function roomTypeId(): string
    {
        return $this->roomTypeId;
    }

    public function discountPercentage(): float
    {
        return $this->discountPercentage;
    }

    public function discountName(): string
    {
        return $this->discountName;
    }

    public function discountCode(): string
    {
        return $this->discountCode;
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function appliedBy(): ?string
    {
        return $this->appliedBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}