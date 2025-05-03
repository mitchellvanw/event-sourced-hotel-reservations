<?php

declare(strict_types=1);

namespace App\Guest\Query;

final readonly class GetGuestById
{
    public function __construct(
        public string $guestId
    ) {
    }
}