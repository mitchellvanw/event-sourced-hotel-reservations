<?php

namespace Accommodation\Query;

class GetActiveSeasonalRates
{
    public function __construct(
        public readonly ?\DateTimeImmutable $forDate = null,
        public readonly ?string $roomTypeId = null,
        public readonly ?string $seasonName = null
    ) {
    }
}