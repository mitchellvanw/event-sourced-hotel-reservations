<?php

declare(strict_types=1);

namespace App\Accommodation\Command;

final readonly class UpdateRoomRate
{
    public function __construct(
        public string $roomId,
        public float $rate
    ) {
    }
}