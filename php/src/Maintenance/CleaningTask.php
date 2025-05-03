<?php

declare(strict_types=1);

namespace App\Maintenance;

use App\Maintenance\DomainEvent\CleaningRequested;
use App\Maintenance\DomainEvent\CleaningCompleted;

final class CleaningTask
{
    private function __construct(
        private CleaningTaskId $id,
        private string $roomId,
        private string $status,
        private string $priority,
        private ?string $assignedTo = null,
        private ?string $notes = null,
        private ?\DateTimeImmutable $completedAt = null
    ) {
    }
    
    public static function request(
        CleaningTaskId $id,
        string $roomId,
        string $priority = 'normal'
    ): array {
        $event = new CleaningRequested(
            $id->toString(),
            $roomId,
            $priority,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function complete(string $staffId, ?string $notes = null): array
    {
        if ($this->status === 'completed') {
            throw new \DomainException('Cleaning task is already completed');
        }
        
        $event = new CleaningCompleted(
            $this->id->toString(),
            $this->roomId,
            $staffId,
            $notes,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyCleaningRequested(CleaningRequested $event): self
    {
        return new self(
            CleaningTaskId::fromString($event->taskId),
            $event->roomId,
            'pending',
            $event->priority ?? 'normal'
        );
    }
    
    public function applyCleaningCompleted(CleaningCompleted $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            'completed',
            $this->priority,
            $event->staffId,
            $event->notes,
            $event->timestamp
        );
    }
}