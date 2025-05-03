<?php

declare(strict_types=1);

namespace App\Reservation\Command;

final readonly class ConfirmBooking
{
    public function __construct(
        public string $id,
    ) {
    }
}