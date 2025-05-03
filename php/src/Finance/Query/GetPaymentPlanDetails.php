<?php

namespace Finance\Query;

class GetPaymentPlanDetails
{
    public function __construct(
        public readonly string $invoiceId
    ) {
    }
}