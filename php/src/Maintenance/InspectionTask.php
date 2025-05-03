<?php

declare(strict_types=1);

namespace App\Maintenance;

use App\Maintenance\DomainEvent\InspectionScheduled;
use App\Maintenance\DomainEvent\InspectionCompleted;

final class InspectionTask
{
    private function __construct(
        private InspectionTaskId $id,
        private string $roomId,
        private string $inspectionType,
        private string $status,
        private \DateTimeImmutable $scheduledAt,
        private ?string $inspectorId = null,
        private ?string $result = null,
        private ?array $issues = null,
        private ?\DateTimeImmutable $completedAt = null
    ) {
    }
    
    public static function schedule(
        InspectionTaskId $id,
        string $roomId,
        string $inspectionType,
        \DateTimeImmutable $scheduledAt
    ): array {
        $event = new InspectionScheduled(
            $id->toString(),
            $roomId,
            $inspectionType,
            $scheduledAt,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function complete(string $inspectorId, string $result, array $issues = []): array
    {
        if ($this->status === 'completed') {
            throw new \DomainException('Inspection is already completed');
        }
        
        $event = new InspectionCompleted(
            $this->id->toString(),
            $this->roomId,
            $inspectorId,
            $result,
            $issues,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyInspectionScheduled(InspectionScheduled $event): self
    {
        return new self(
            InspectionTaskId::fromString($event->taskId),
            $event->roomId,
            $event->inspectionType,
            'scheduled',
            $event->scheduledAt
        );
    }
    
    public function applyInspectionCompleted(InspectionCompleted $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            $this->inspectionType,
            'completed',
            $this->scheduledAt,
            $event->inspectorId,
            $event->result,
            $event->issues,
            $event->timestamp
        );
    }
}