<?php

namespace Accommodation\Query;

class GetActiveDiscounts
{
    public function __construct(
        public readonly ?\DateTimeImmutable $forDate = null,
        public readonly ?string $roomTypeId = null,
        public readonly ?string $discountCode = null
    ) {
    }
}