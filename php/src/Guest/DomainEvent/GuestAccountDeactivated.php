<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class GuestAccountDeactivated
{
    public function __construct(
        public string $guestId,
        public string $reason,
        public ?string $deactivatedBy = null,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}