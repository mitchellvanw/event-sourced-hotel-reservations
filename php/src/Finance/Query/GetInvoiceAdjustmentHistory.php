<?php

namespace Finance\Query;

class GetInvoiceAdjustmentHistory
{
    public function __construct(
        public readonly string $invoiceId
    ) {
    }
}