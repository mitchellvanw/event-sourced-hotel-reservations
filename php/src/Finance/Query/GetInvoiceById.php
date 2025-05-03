<?php

declare(strict_types=1);

namespace App\Finance\Query;

final readonly class GetInvoiceById
{
    public function __construct(
        public string $invoiceId
    ) {
    }
}