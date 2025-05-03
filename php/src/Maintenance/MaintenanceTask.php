<?php

declare(strict_types=1);

namespace App\Maintenance;

use App\Maintenance\DomainEvent\MaintenanceRequested;
use App\Maintenance\DomainEvent\MaintenanceCompleted;

final class MaintenanceTask
{
    private function __construct(
        private MaintenanceTaskId $id,
        private string $roomId,
        private string $issue,
        private string $status,
        private string $priority,
        private ?string $assignedTo = null,
        private ?string $resolution = null,
        private ?\DateTimeImmutable $completedAt = null
    ) {
    }
    
    public static function request(
        MaintenanceTaskId $id,
        string $roomId,
        string $issue,
        string $priority = 'normal'
    ): array {
        $event = new MaintenanceRequested(
            $id->toString(),
            $roomId,
            $issue,
            $priority,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function complete(string $staffId, string $resolution): array
    {
        if ($this->status === 'completed') {
            throw new \DomainException('Maintenance task is already completed');
        }
        
        $event = new MaintenanceCompleted(
            $this->id->toString(),
            $this->roomId,
            $staffId,
            $resolution,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyMaintenanceRequested(MaintenanceRequested $event): self
    {
        return new self(
            MaintenanceTaskId::fromString($event->taskId),
            $event->roomId,
            $event->issue,
            'pending',
            $event->priority ?? 'normal'
        );
    }
    
    public function applyMaintenanceCompleted(MaintenanceCompleted $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            $this->issue,
            'completed',
            $this->priority,
            $event->staffId,
            $event->resolution,
            $event->timestamp
        );
    }
}