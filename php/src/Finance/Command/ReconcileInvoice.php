<?php

namespace Finance\Command;

class ReconcileInvoice
{
    public function __construct(
        public readonly string $invoiceId,
        public readonly array $reconciliationDetails,
        public readonly ?string $reconciledBy = null
    ) {
    }
}