<?php

declare(strict_types=1);

namespace App\Accommodation\DomainEvent;

final readonly class RoomStatusChanged
{
    public function __construct(
        public string $roomId,
        public string $oldStatus,
        public string $newStatus,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}