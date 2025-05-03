<?php

declare(strict_types=1);

namespace App\Accommodation\Command;

final readonly class CreateRoom
{
    public function __construct(
        public string $roomNumber,
        public string $type,
        public float $rate
    ) {
    }
}