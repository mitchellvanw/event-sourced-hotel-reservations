<?php

declare(strict_types=1);

namespace App\Reservation;

final readonly class NoShowRecorded
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}