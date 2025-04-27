<?php

declare(strict_types=1);

namespace App\Room;

final readonly class RoomCategoryChanged
{
    public function __construct(
        public string $roomId,
        public string $oldCategory,
        public string $newCategory,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}