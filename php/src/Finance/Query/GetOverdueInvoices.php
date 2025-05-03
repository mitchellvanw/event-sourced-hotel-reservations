<?php

namespace Finance\Query;

class GetOverdueInvoices
{
    public function __construct(
        public readonly ?\DateTimeImmutable $asOfDate = null,
        public readonly ?int $daysOverdue = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}