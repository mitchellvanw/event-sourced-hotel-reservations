<?php

declare(strict_types=1);

namespace App\Finance\DomainEvent;

final readonly class RefundIssued
{
    public function __construct(
        public string $paymentId,
        public string $invoiceId,
        public string $guestId,
        public float $amount,
        public string $reason,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}