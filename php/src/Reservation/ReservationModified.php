<?php

declare(strict_types=1);

namespace App\Reservation;

final readonly class ReservationModified
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $checkInDate,
        public \DateTimeImmutable $checkOutDate,
        public ?string $notes,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}