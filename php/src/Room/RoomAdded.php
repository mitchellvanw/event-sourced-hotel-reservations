<?php

declare(strict_types=1);

namespace App\Room;

final readonly class RoomAdded
{
    public function __construct(
        public string $roomId,
        public string $roomNumber,
        public string $category,
        public float $price,
        public string $status,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}