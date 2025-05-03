<?php

declare(strict_types=1);

namespace App\Accommodation;

use App\Accommodation\DomainEvent\RoomCreated;
use App\Accommodation\DomainEvent\RoomStatusChanged;
use App\Accommodation\DomainEvent\RoomTypeChanged;
use App\Accommodation\DomainEvent\RoomRateUpdated;

final class Room
{
    private function __construct(
        private RoomId $id,
        private string $roomNumber,
        private string $type,
        private string $status,
        private float $rate
    ) {
    }
    
    public static function create(
        RoomId $id,
        string $roomNumber,
        string $type,
        float $rate
    ): array {
        $event = new RoomCreated(
            $id->toString(),
            $roomNumber,
            $type,
            $rate,
            'available',
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function changeStatus(string $newStatus): array
    {
        if ($this->status === $newStatus) {
            return [];
        }
        
        $event = new RoomStatusChanged(
            $this->id->toString(),
            $this->status,
            $newStatus,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function changeType(string $newType): array
    {
        if ($this->type === $newType) {
            return [];
        }
        
        $event = new RoomTypeChanged(
            $this->id->toString(),
            $this->type,
            $newType,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function updateRate(float $newRate): array
    {
        if ($this->rate === $newRate) {
            return [];
        }
        
        $event = new RoomRateUpdated(
            $this->id->toString(),
            $this->rate,
            $newRate,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyRoomCreated(RoomCreated $event): self
    {
        return new self(
            RoomId::fromString($event->roomId),
            $event->roomNumber,
            $event->type,
            $event->status,
            $event->rate
        );
    }
    
    public function applyRoomStatusChanged(RoomStatusChanged $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $this->type,
            $event->newStatus,
            $this->rate
        );
    }
    
    public function applyRoomTypeChanged(RoomTypeChanged $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $event->newType,
            $this->status,
            $this->rate
        );
    }
    
    public function applyRoomRateUpdated(RoomRateUpdated $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $this->type,
            $this->status,
            $event->newRate
        );
    }
}