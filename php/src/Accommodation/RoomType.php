<?php

declare(strict_types=1);

namespace App\Accommodation;

use App\Accommodation\DomainEvent\RoomTypeDefined;

final class RoomType
{
    private function __construct(
        private RoomTypeId $id,
        private string $name,
        private string $description,
        private array $amenities,
        private int $capacity
    ) {
    }
    
    public static function define(
        RoomTypeId $id,
        string $name,
        string $description,
        array $amenities,
        int $capacity
    ): array {
        $event = new RoomTypeDefined(
            $id->toString(),
            $name,
            $description,
            $amenities,
            $capacity,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyRoomTypeDefined(RoomTypeDefined $event): self
    {
        return new self(
            RoomTypeId::fromString($event->roomTypeId),
            $event->name,
            $event->description,
            $event->amenities,
            $event->capacity
        );
    }
}