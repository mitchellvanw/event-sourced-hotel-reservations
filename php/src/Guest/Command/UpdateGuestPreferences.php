<?php

declare(strict_types=1);

namespace App\Guest\Command;

final readonly class UpdateGuestPreferences
{
    public function __construct(
        public string $guestId,
        public array $preferences
    ) {
    }
}