<?php

declare(strict_types=1);

namespace App\Finance\Command;

final readonly class RecordPayment
{
    public function __construct(
        public string $invoiceId,
        public string $guestId,
        public float $amount,
        public string $method
    ) {
    }
}