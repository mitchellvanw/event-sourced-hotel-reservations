<?php

namespace Finance\DomainEvent;

class PaymentPlanCreated
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $invoiceId,
        private string $paymentPlanId,
        private array $installments,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private ?string $createdBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function invoiceId(): string
    {
        return $this->invoiceId;
    }

    public function paymentPlanId(): string
    {
        return $this->paymentPlanId;
    }

    public function installments(): array
    {
        return $this->installments;
    }

    public function startDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function endDate(): \DateTimeImmutable
    {
        return $this->endDate;
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