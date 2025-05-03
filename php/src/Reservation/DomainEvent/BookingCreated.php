<?php

declare(strict_types=1);

namespace App\Reservation\DomainEvent;

final readonly class BookingCreated
{
    public function __construct(
        public string $id,
        public string $guestId,
        public string $roomId,
        public \DateTimeImmutable $checkInDate,
        public \DateTimeImmutable $checkOutDate,
        public ?string $notes,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}