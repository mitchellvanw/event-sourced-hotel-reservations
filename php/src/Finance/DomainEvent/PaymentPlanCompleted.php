<?php

namespace Finance\DomainEvent;

class PaymentPlanCompleted
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $paymentPlanId,
        private string $invoiceId,
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

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}