<?php

declare(strict_types=1);

namespace App\Accommodation\Command;

final readonly class DefineRoomType
{
    public function __construct(
        public string $name,
        public string $description,
        public array $amenities,
        public int $capacity
    ) {
    }
}