<?php

declare(strict_types=1);

namespace App\Reservation;

final readonly class CheckInPerformed
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}