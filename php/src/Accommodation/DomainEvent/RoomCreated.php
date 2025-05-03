<?php

declare(strict_types=1);

namespace App\Accommodation\DomainEvent;

final readonly class RoomCreated
{
    public function __construct(
        public string $roomId,
        public string $roomNumber,
        public string $type,
        public float $rate,
        public string $status,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}