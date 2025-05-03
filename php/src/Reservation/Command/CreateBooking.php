<?php

declare(strict_types=1);

namespace App\Reservation\Command;

final readonly class CreateBooking
{
    public function __construct(
        public string $id,
        public string $guestId,
        public string $roomId,
        public \DateTimeImmutable $checkInDate,
        public \DateTimeImmutable $checkOutDate,
        public ?string $notes = null,
    ) {
    }
}