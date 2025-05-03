<?php

namespace Finance\Command;

class AdjustInvoice
{
    public function __construct(
        public readonly string $invoiceId,
        public readonly array $adjustments,
        public readonly string $reason,
        public readonly ?string $adjustedBy = null
    ) {
    }
}