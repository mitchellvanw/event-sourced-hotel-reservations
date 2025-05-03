<?php

declare(strict_types=1);

namespace App\Finance\DomainEvent;

final readonly class InvoiceCreated
{
    public function __construct(
        public string $invoiceId,
        public string $reservationId,
        public string $guestId,
        public float $amount,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}