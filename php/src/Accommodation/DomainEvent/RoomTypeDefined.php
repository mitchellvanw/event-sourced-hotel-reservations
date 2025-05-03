<?php

declare(strict_types=1);

namespace App\Accommodation\DomainEvent;

final readonly class RoomTypeDefined
{
    public function __construct(
        public string $roomTypeId,
        public string $name,
        public string $description,
        public array $amenities,
        public int $capacity,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}