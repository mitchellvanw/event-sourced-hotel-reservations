<?php

declare(strict_types=1);

namespace App\Accommodation\DomainEvent;

final readonly class RoomTypeChanged
{
    public function __construct(
        public string $roomId,
        public string $oldType,
        public string $newType,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}