<?php

namespace Finance\Query;

class GetInvoicePaymentHistory
{
    public function __construct(
        public readonly string $invoiceId
    ) {
    }
}