<?php

namespace Finance\DomainEvent;

class InstallmentPaid
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $paymentPlanId,
        private string $invoiceId,
        private int $installmentIndex,
        private float $amount,
        private \DateTimeImmutable $dueDate,
        private \DateTimeImmutable $paymentDate,
        private ?string $notes = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function paymentPlanId(): string
    {
        return $this->paymentPlanId;
    }

    public function invoiceId(): string
    {
        return $this->invoiceId;
    }

    public function installmentIndex(): int
    {
        return $this->installmentIndex;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function dueDate(): \DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function paymentDate(): \DateTimeImmutable
    {
        return $this->paymentDate;
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