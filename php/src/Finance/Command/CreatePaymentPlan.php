<?php

namespace Finance\Command;

class CreatePaymentPlan
{
    public function __construct(
        public readonly string $invoiceId,
        public readonly string $paymentPlanId,
        public readonly array $installments,
        public readonly \DateTimeImmutable $startDate,
        public readonly \DateTimeImmutable $endDate,
        public readonly ?string $createdBy = null
    ) {
    }
}