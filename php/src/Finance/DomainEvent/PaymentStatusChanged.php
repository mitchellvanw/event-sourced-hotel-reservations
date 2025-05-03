<?php

namespace Finance\DomainEvent;

class PaymentStatusChanged
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $invoiceId,
        private string $oldStatus,
        private string $newStatus,
        private ?string $reason = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function invoiceId(): string
    {
        return $this->invoiceId;
    }

    public function oldStatus(): string
    {
        return $this->oldStatus;
    }

    public function newStatus(): string
    {
        return $this->newStatus;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}