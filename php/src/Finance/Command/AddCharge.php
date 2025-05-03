<?php

declare(strict_types=1);

namespace App\Finance\Command;

final readonly class AddCharge
{
    public function __construct(
        public string $invoiceId,
        public string $description,
        public float $amount
    ) {
    }
}