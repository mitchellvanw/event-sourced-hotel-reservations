<?php

declare(strict_types=1);

namespace App\Reservation\DomainEvent;

final readonly class BookingFulfilled
{
    public function __construct(
        public string $bookingId,
        public string $stayId,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}