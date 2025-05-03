<?php

namespace Accommodation\Query;

class GetAvailableRoomsWithFeatures
{
    public function __construct(
        public readonly array $requiredFeatures,
        public readonly ?\DateTimeImmutable $startDate = null,
        public readonly ?\DateTimeImmutable $endDate = null,
        public readonly ?string $roomType = null,
        public readonly ?float $maxRate = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}