<?php

namespace Accommodation\DomainEvent;

class SeasonalRateCreated
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $rateId,
        private string $roomTypeId,
        private float $rate,
        private string $seasonName,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private ?string $description = null,
        private ?string $createdBy = null,
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

    public function rate(): float
    {
        return $this->rate;
    }

    public function seasonName(): string
    {
        return $this->seasonName;
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function createdBy(): ?string
    {
        return $this->createdBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}