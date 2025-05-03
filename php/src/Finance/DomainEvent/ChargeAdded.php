<?php

declare(strict_types=1);

namespace App\Finance\DomainEvent;

final readonly class ChargeAdded
{
    public function __construct(
        public string $invoiceId,
        public string $description,
        public float $amount,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}