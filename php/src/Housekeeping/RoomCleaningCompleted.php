<?php

declare(strict_types=1);

namespace App\Housekeeping;

final readonly class RoomCleaningCompleted
{
    public function __construct(
        public string $requestId,
        public string $roomId,
        public string $staffId,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}