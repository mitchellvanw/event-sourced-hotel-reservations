<?php

namespace Finance\Command;

class MarkInvoiceAsOverdue
{
    public function __construct(
        public readonly string $invoiceId
    ) {
    }
}