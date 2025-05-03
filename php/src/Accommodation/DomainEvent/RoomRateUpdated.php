<?php

declare(strict_types=1);

namespace App\Accommodation\DomainEvent;

final readonly class RoomRateUpdated
{
    public function __construct(
        public string $roomId,
        public float $oldRate,
        public float $newRate,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}