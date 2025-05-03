<?php

namespace Finance\Command;

class CancelPaymentPlan
{
    public function __construct(
        public readonly string $paymentPlanId,
        public readonly string $reason
    ) {
    }
}