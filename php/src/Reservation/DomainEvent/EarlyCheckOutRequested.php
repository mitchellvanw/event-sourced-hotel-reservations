<?php

declare(strict_types=1);

namespace App\Reservation\DomainEvent;

final readonly class EarlyCheckOutRequested
{
    public function __construct(
        public string $stayId,
        public string $bookingId,
        public \DateTimeImmutable $originalCheckOutDate,
        public \DateTimeImmutable $newCheckOutDate,
        public ?string $reason = null,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}