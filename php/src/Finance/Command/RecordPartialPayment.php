<?php

namespace Finance\Command;

class RecordPartialPayment
{
    public function __construct(
        public readonly string $invoiceId,
        public readonly string $paymentId,
        public readonly float $amount,
        public readonly string $method,
        public readonly ?string $notes = null
    ) {
    }
}