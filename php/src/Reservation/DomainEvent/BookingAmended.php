<?php

declare(strict_types=1);

namespace App\Reservation\DomainEvent;

final readonly class BookingAmended
{
    public function __construct(
        public string $bookingId,
        public string $amendmentType,
        public string $amendmentDetails,
        public string $amendedBy,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}