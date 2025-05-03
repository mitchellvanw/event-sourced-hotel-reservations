<?php

namespace Finance\Command;

class RecordInstallmentPayment
{
    public function __construct(
        public readonly string $paymentPlanId,
        public readonly int $installmentIndex,
        public readonly \DateTimeImmutable $paymentDate,
        public readonly ?string $notes = null
    ) {
    }
}