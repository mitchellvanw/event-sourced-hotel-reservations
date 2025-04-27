<?php

declare(strict_types=1);

namespace App\Guest;

final readonly class GuestProfileUpdated
{
    public function __construct(
        public string $guestId,
        public string $name,
        public string $email,
        public ?string $phone,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}