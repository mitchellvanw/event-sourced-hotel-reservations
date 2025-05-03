<?php

namespace Accommodation\DomainEvent;

class SeasonalRateUpdated
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $rateId,
        private string $roomTypeId,
        private float $oldRate,
        private float $newRate,
        private ?\DateTimeImmutable $oldStartDate = null,
        private ?\DateTimeImmutable $newStartDate = null,
        private ?\DateTimeImmutable $oldEndDate = null,
        private ?\DateTimeImmutable $newEndDate = null,
        private ?string $reason = null,
        private ?string $updatedBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function rateId(): string
    {
        return $this->rateId;
    }

    public function roomTypeId(): string
    {
        return $this->roomTypeId;
    }

    public function oldRate(): float
    {
        return $this->oldRate;
    }

    public function newRate(): float
    {
        return $this->newRate;
    }

    public function oldStartDate(): ?\DateTimeImmutable
    {
        return $this->oldStartDate;
    }

    public function newStartDate(): ?\DateTimeImmutable
    {
        return $this->newStartDate;
    }

    public function oldEndDate(): ?\DateTimeImmutable
    {
        return $this->oldEndDate;
    }

    public function newEndDate(): ?\DateTimeImmutable
    {
        return $this->newEndDate;
    }

    public function reason(): ?string
    {
        return $this->reason;
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