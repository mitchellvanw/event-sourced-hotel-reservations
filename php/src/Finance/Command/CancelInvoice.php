<?php

namespace Finance\Command;

class CancelInvoice
{
    public function __construct(
        public readonly string $invoiceId,
        public readonly string $reason,
        public readonly ?string $cancelledBy = null
    ) {
    }
}