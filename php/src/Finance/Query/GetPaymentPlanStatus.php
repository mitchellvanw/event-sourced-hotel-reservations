<?php

namespace Finance\Query;

class GetPaymentPlanStatus
{
    public function __construct(
        public readonly string $paymentPlanId
    ) {
    }
}