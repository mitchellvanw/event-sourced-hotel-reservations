<?php

declare(strict_types=1);

namespace App\Finance\Command;

final readonly class CreateInvoice
{
    public function __construct(
        public string $reservationId,
        public string $guestId,
        public float $initialAmount = 0
    ) {
    }
}