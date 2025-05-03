<?php

declare(strict_types=1);

namespace App\Finance\Command;

final readonly class IssueRefund
{
    public function __construct(
        public string $paymentId,
        public string $reason
    ) {
    }
}