<?php

declare(strict_types=1);

namespace App\Room;

final readonly class Room
{
    private string $roomId;
    private string $roomNumber;
    private string $category;
    private string $status;
    private float $price;
    
    private function __construct(string $roomId)
    {
        $this->roomId = $roomId;
    }
    
    public static function create(string $roomId, string $roomNumber, string $category, float $price): array
    {
        $room = new self($roomId);
        $event = new RoomAdded(
            roomId: $roomId,
            roomNumber: $roomNumber,
            category: $category,
            price: $price,
            status: 'available',
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$room->applyRoomAdded($event), $event];
    }
    
    public function changeStatus(string $newStatus): array
    {
        if ($this->status === $newStatus) {
            return [$this, null];
        }
        
        $event = new RoomStatusChanged(
            roomId: $this->roomId,
            oldStatus: $this->status,
            newStatus: $newStatus,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyRoomStatusChanged($event), $event];
    }
    
    public function changeCategory(string $newCategory): array
    {
        if ($this->category === $newCategory) {
            return [$this, null];
        }
        
        $event = new RoomCategoryChanged(
            roomId: $this->roomId,
            oldCategory: $this->category,
            newCategory: $newCategory,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyRoomCategoryChanged($event), $event];
    }
    
    public function updatePrice(float $newPrice): array
    {
        if ($this->price === $newPrice) {
            return [$this, null];
        }
        
        $event = new RoomPriceUpdated(
            roomId: $this->roomId,
            oldPrice: $this->price,
            newPrice: $newPrice,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyRoomPriceUpdated($event), $event];
    }
    
    public function applyRoomAdded(RoomAdded $event): self
    {
        $room = clone $this;
        $room->roomNumber = $event->roomNumber;
        $room->category = $event->category;
        $room->status = $event->status;
        $room->price = $event->price;
        
        return $room;
    }
    
    public function applyRoomStatusChanged(RoomStatusChanged $event): self
    {
        $room = clone $this;
        $room->status = $event->newStatus;
        
        return $room;
    }
    
    public function applyRoomCategoryChanged(RoomCategoryChanged $event): self
    {
        $room = clone $this;
        $room->category = $event->newCategory;
        
        return $room;
    }
    
    public function applyRoomPriceUpdated(RoomPriceUpdated $event): self
    {
        $room = clone $this;
        $room->price = $event->newPrice;
        
        return $room;
    }
}