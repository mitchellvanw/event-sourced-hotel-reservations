<?php

declare(strict_types=1);

namespace App\Housekeeping;

final readonly class Housekeeping
{
    private string $requestId;
    private string $roomId;
    private string $requestType;
    private string $status;
    private ?string $assignedTo;
    private ?\DateTimeImmutable $completedAt;
    
    private function __construct(string $requestId)
    {
        $this->requestId = $requestId;
    }
    
    public static function requestCleaning(string $requestId, string $roomId): array
    {
        $housekeeping = new self($requestId);
        $event = new RoomCleaningRequested(
            requestId: $requestId,
            roomId: $roomId,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$housekeeping->applyRoomCleaningRequested($event), $event];
    }
    
    public static function requestMaintenance(string $requestId, string $roomId, string $issue): array
    {
        $housekeeping = new self($requestId);
        $event = new MaintenanceRequested(
            requestId: $requestId,
            roomId: $roomId,
            issue: $issue,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$housekeeping->applyMaintenanceRequested($event), $event];
    }
    
    public function completeCleaning(string $staffId): array
    {
        if ($this->requestType !== 'cleaning') {
            throw new \InvalidArgumentException('Request is not for cleaning');
        }
        
        if ($this->status === 'completed') {
            return [$this, null];
        }
        
        $event = new RoomCleaningCompleted(
            requestId: $this->requestId,
            roomId: $this->roomId,
            staffId: $staffId,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyRoomCleaningCompleted($event), $event];
    }
    
    public function applyRoomCleaningRequested(RoomCleaningRequested $event): self
    {
        $housekeeping = clone $this;
        $housekeeping->roomId = $event->roomId;
        $housekeeping->requestType = 'cleaning';
        $housekeeping->status = 'pending';
        $housekeeping->assignedTo = null;
        $housekeeping->completedAt = null;
        
        return $housekeeping;
    }
    
    public function applyMaintenanceRequested(MaintenanceRequested $event): self
    {
        $housekeeping = clone $this;
        $housekeeping->roomId = $event->roomId;
        $housekeeping->requestType = 'maintenance';
        $housekeeping->status = 'pending';
        $housekeeping->assignedTo = null;
        $housekeeping->completedAt = null;
        
        return $housekeeping;
    }
    
    public function applyRoomCleaningCompleted(RoomCleaningCompleted $event): self
    {
        $housekeeping = clone $this;
        $housekeeping->status = 'completed';
        $housekeeping->assignedTo = $event->staffId;
        $housekeeping->completedAt = $event->timestamp;
        
        return $housekeeping;
    }
}