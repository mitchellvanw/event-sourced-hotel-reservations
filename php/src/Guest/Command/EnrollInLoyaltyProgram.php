<?php

declare(strict_types=1);

namespace App\Guest\Command;

final readonly class EnrollInLoyaltyProgram
{
    public function __construct(
        public string $guestId,
        public string $tier = 'standard'
    ) {
    }
}