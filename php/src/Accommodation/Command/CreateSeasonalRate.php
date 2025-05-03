<?php

namespace Accommodation\Command;

class CreateSeasonalRate
{
    public function __construct(
        public readonly string $rateId,
        public readonly string $roomTypeId,
        public readonly float $rate,
        public readonly string $seasonName,
        public readonly \DateTimeImmutable $startDate,
        public readonly \DateTimeImmutable $endDate,
        public readonly ?string $description = null,
        public readonly ?string $createdBy = null
    ) {
    }
}