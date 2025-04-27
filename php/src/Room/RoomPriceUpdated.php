<?php

declare(strict_types=1);

namespace App\Room;

final readonly class RoomPriceUpdated
{
    public function __construct(
        public string $roomId,
        public float $oldPrice,
        public float $newPrice,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}