<?php

declare(strict_types=1);

namespace App\Housekeeping;

final readonly class RoomCleaningRequested
{
    public function __construct(
        public string $requestId,
        public string $roomId,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}