<?php

namespace Finance\DomainEvent;

class InvoiceReconciled
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $invoiceId,
        private array $reconciliationDetails,
        private ?string $reconciledBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function invoiceId(): string
    {
        return $this->invoiceId;
    }

    public function reconciliationDetails(): array
    {
        return $this->reconciliationDetails;
    }

    public function reconciledBy(): ?string
    {
        return $this->reconciledBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}