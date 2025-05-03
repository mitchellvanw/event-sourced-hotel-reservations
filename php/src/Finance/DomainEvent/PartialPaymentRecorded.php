<?php

namespace Finance\DomainEvent;

class PartialPaymentRecorded
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $invoiceId,
        private string $paymentId,
        private float $amount,
        private string $method,
        private float $remainingBalance,
        private ?string $notes = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function invoiceId(): string
    {
        return $this->invoiceId;
    }

    public function paymentId(): string
    {
        return $this->paymentId;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function remainingBalance(): float
    {
        return $this->remainingBalance;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}