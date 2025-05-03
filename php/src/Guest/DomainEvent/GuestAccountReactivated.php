<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class GuestAccountReactivated
{
    public function __construct(
        public string $guestId,
        public ?string $reactivatedBy = null,
        public ?string $notes = null,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}