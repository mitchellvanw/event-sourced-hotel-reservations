<?php

namespace Accommodation\Command;

class UpdateSeasonalRate
{
    public function __construct(
        public readonly string $rateId,
        public readonly ?float $newRate = null,
        public readonly ?\DateTimeImmutable $newStartDate = null,
        public readonly ?\DateTimeImmutable $newEndDate = null,
        public readonly ?string $reason = null,
        public readonly ?string $updatedBy = null
    ) {
    }
}