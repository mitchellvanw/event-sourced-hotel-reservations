<?php

namespace Finance\DomainEvent;

class InvoiceCancelled
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $invoiceId,
        private string $reason,
        private ?string $cancelledBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function invoiceId(): string
    {
        return $this->invoiceId;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function cancelledBy(): ?string
    {
        return $this->cancelledBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}