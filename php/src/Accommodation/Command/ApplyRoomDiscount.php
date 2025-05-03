<?php

namespace Accommodation\Command;

class ApplyRoomDiscount
{
    public function __construct(
        public readonly string $discountId,
        public readonly string $roomTypeId,
        public readonly float $discountPercentage,
        public readonly string $discountName,
        public readonly string $discountCode,
        public readonly \DateTimeImmutable $startDate,
        public readonly \DateTimeImmutable $endDate,
        public readonly ?string $appliedBy = null
    ) {
    }
}