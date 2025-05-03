<?php

declare(strict_types=1);

namespace App\Finance\DomainEvent;

final readonly class InvoiceFinalized
{
    public function __construct(
        public string $invoiceId,
        public float $totalAmount,
        public \DateTimeImmutable $dueDate,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}