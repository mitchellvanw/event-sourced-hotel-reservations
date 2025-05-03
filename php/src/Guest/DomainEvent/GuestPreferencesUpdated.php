<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class GuestPreferencesUpdated
{
    public function __construct(
        public string $guestId,
        public array $oldPreferences,
        public array $newPreferences,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}