<?php

declare(strict_types=1);

namespace App\Finance\Query;

final readonly class GetPaymentHistory
{
    public function __construct(
        public ?string $guestId = null,
        public ?string $invoiceId = null,
        public ?\DateTimeImmutable $startDate = null,
        public ?\DateTimeImmutable $endDate = null,
        public ?int $limit = 10,
        public ?int $offset = 0
    ) {
    }
}