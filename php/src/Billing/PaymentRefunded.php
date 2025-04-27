<?php

declare(strict_types=1);

namespace App\Billing;

final readonly class PaymentRefunded
{
    public function __construct(
        public string $billingId,
        public string $reservationId,
        public string $guestId,
        public float $amount,
        public string $reason,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}