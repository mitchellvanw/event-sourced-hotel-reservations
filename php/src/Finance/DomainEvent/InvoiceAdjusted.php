<?php

namespace Finance\DomainEvent;

class InvoiceAdjusted
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $invoiceId,
        private array $adjustments,
        private string $reason,
        private float $oldTotal,
        private float $newTotal,
        private ?string $adjustedBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function invoiceId(): string
    {
        return $this->invoiceId;
    }

    public function adjustments(): array
    {
        return $this->adjustments;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function oldTotal(): float
    {
        return $this->oldTotal;
    }

    public function newTotal(): float
    {
        return $this->newTotal;
    }

    public function adjustedBy(): ?string
    {
        return $this->adjustedBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}