<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class GuestRegistered
{
    public function __construct(
        public string $guestId,
        public string $name,
        public string $email,
        public ?string $phone,
        public array $preferences,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}