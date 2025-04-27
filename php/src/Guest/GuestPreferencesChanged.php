<?php

declare(strict_types=1);

namespace App\Guest;

final readonly class GuestPreferencesChanged
{
    public function __construct(
        public string $guestId,
        public array $oldPreferences,
        public array $newPreferences,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}