<?php

declare(strict_types=1);

namespace Maintenance;

use Maintenance\DomainEvent\MaintenanceRequested;
use Maintenance\DomainEvent\MaintenanceCompleted;
use Maintenance\DomainEvent\TaskAssigned;
use Maintenance\DomainEvent\TaskRescheduled;
use Maintenance\DomainEvent\TaskEscalated;
use Maintenance\DomainEvent\TaskCancelled;

final class MaintenanceTask
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ASSIGNED = 'assigned';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ESCALATED = 'escalated';
    public const STATUS_CANCELLED = 'cancelled';

    private function __construct(
        private MaintenanceTaskId $id,
        private string $roomId,
        private string $issue,
        private string $status,
        private string $priority,
        private ?string $assignedTo = null,
        private ?string $resolution = null,
        private ?\DateTimeImmutable $scheduledFor = null,
        private ?int $escalationLevel = null,
        private ?string $escalationReason = null,
        private ?string $cancellationReason = null,
        private array $statusHistory = [],
        private ?\DateTimeImmutable $requestedAt = null,
        private ?\DateTimeImmutable $assignedAt = null,
        private ?\DateTimeImmutable $startedAt = null,
        private ?\DateTimeImmutable $completedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null
    ) {
    }
    
    public static function request(
        MaintenanceTaskId $id,
        string $roomId,
        string $issue,
        string $priority = 'normal',
        ?\DateTimeImmutable $scheduledFor = null
    ): array {
        $event = new MaintenanceRequested(
            $id->toString(),
            $roomId,
            $issue,
            $priority,
            $scheduledFor,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function complete(string $staffId, string $resolution): array
    {
        if ($this->status === self::STATUS_COMPLETED) {
            throw new \DomainException('Maintenance task is already completed');
        }
        
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot complete a cancelled task');
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
    
    public function assign(string $staffId, ?string $notes = null): array
    {
        if ($this->status === self::STATUS_COMPLETED) {
            throw new \DomainException('Cannot assign a completed task');
        }
        
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot assign a cancelled task');
        }
        
        $event = new TaskAssigned(
            $this->id->toString(),
            'maintenance',
            $staffId,
            $notes,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function reschedule(
        \DateTimeImmutable $newScheduledTime,
        string $reason,
        ?string $rescheduledBy = null
    ): array {
        if ($this->status === self::STATUS_COMPLETED) {
            throw new \DomainException('Cannot reschedule a completed task');
        }
        
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot reschedule a cancelled task');
        }
        
        if ($this->scheduledFor === null) {
            throw new \DomainException('Task does not have a scheduled time to reschedule');
        }
        
        if ($newScheduledTime <= new \DateTimeImmutable()) {
            throw new \InvalidArgumentException('New scheduled time must be in the future');
        }
        
        $event = new TaskRescheduled(
            $this->id->toString(),
            'maintenance',
            $this->scheduledFor,
            $newScheduledTime,
            $reason,
            $rescheduledBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function escalate(
        string $reason,
        int $severityLevel,
        ?string $escalatedBy = null,
        ?string $notes = null
    ): array {
        if ($this->status === self::STATUS_COMPLETED) {
            throw new \DomainException('Cannot escalate a completed task');
        }
        
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot escalate a cancelled task');
        }
        
        if ($severityLevel < 1 || $severityLevel > 5) {
            throw new \InvalidArgumentException('Severity level must be between 1 and 5');
        }
        
        if ($this->escalationLevel !== null && $severityLevel <= $this->escalationLevel) {
            throw new \DomainException('New severity level must be higher than current level');
        }
        
        $event = new TaskEscalated(
            $this->id->toString(),
            'maintenance',
            $reason,
            $severityLevel,
            $escalatedBy,
            $notes,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function cancel(string $reason, ?string $cancelledBy = null): array
    {
        if ($this->status === self::STATUS_COMPLETED) {
            throw new \DomainException('Cannot cancel a completed task');
        }
        
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Task is already cancelled');
        }
        
        $event = new TaskCancelled(
            $this->id->toString(),
            'maintenance',
            $reason,
            $cancelledBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyMaintenanceRequested(MaintenanceRequested $event): self
    {
        return new self(
            MaintenanceTaskId::fromString($event->taskId()),
            $event->roomId(),
            $event->issue(),
            self::STATUS_PENDING,
            $event->priority() ?? 'normal',
            null,
            null,
            $event->scheduledFor(),
            null,
            null,
            null,
            [
                [
                    'status' => self::STATUS_PENDING,
                    'timestamp' => $event->occurredAt()
                ]
            ],
            $event->occurredAt()
        );
    }
    
    public function applyMaintenanceCompleted(MaintenanceCompleted $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            $this->issue,
            self::STATUS_COMPLETED,
            $this->priority,
            $event->staffId(),
            $event->resolution(),
            $this->scheduledFor,
            $this->escalationLevel,
            $this->escalationReason,
            $this->cancellationReason,
            array_merge($this->statusHistory, [
                [
                    'status' => self::STATUS_COMPLETED,
                    'timestamp' => $event->occurredAt()
                ]
            ]),
            $this->requestedAt,
            $this->assignedAt,
            $this->startedAt,
            $event->occurredAt(),
            $this->cancelledAt
        );
    }
    
    public function applyTaskAssigned(TaskAssigned $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            $this->issue,
            self::STATUS_ASSIGNED,
            $this->priority,
            $event->assignedTo(),
            $this->resolution,
            $this->scheduledFor,
            $this->escalationLevel,
            $this->escalationReason,
            $this->cancellationReason,
            array_merge($this->statusHistory, [
                [
                    'status' => self::STATUS_ASSIGNED,
                    'assignedTo' => $event->assignedTo(),
                    'notes' => $event->notes(),
                    'timestamp' => $event->occurredAt()
                ]
            ]),
            $this->requestedAt,
            $event->occurredAt(),
            $this->startedAt,
            $this->completedAt,
            $this->cancelledAt
        );
    }
    
    public function applyTaskRescheduled(TaskRescheduled $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            $this->issue,
            $this->status,
            $this->priority,
            $this->assignedTo,
            $this->resolution,
            $event->newScheduledTime(),
            $this->escalationLevel,
            $this->escalationReason,
            $this->cancellationReason,
            array_merge($this->statusHistory, [
                [
                    'type' => 'reschedule',
                    'oldScheduledTime' => $event->oldScheduledTime(),
                    'newScheduledTime' => $event->newScheduledTime(),
                    'reason' => $event->reason(),
                    'by' => $event->rescheduledBy(),
                    'timestamp' => $event->occurredAt()
                ]
            ]),
            $this->requestedAt,
            $this->assignedAt,
            $this->startedAt,
            $this->completedAt,
            $this->cancelledAt
        );
    }
    
    public function applyTaskEscalated(TaskEscalated $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            $this->issue,
            self::STATUS_ESCALATED,
            $this->priority,
            $this->assignedTo,
            $this->resolution,
            $this->scheduledFor,
            $event->severityLevel(),
            $event->reason(),
            $this->cancellationReason,
            array_merge($this->statusHistory, [
                [
                    'status' => self::STATUS_ESCALATED,
                    'severityLevel' => $event->severityLevel(),
                    'reason' => $event->reason(),
                    'by' => $event->escalatedBy(),
                    'notes' => $event->notes(),
                    'timestamp' => $event->occurredAt()
                ]
            ]),
            $this->requestedAt,
            $this->assignedAt,
            $this->startedAt,
            $this->completedAt,
            $this->cancelledAt
        );
    }
    
    public function applyTaskCancelled(TaskCancelled $event): self
    {
        return new self(
            $this->id,
            $this->roomId,
            $this->issue,
            self::STATUS_CANCELLED,
            $this->priority,
            $this->assignedTo,
            $this->resolution,
            $this->scheduledFor,
            $this->escalationLevel,
            $this->escalationReason,
            $event->reason(),
            array_merge($this->statusHistory, [
                [
                    'status' => self::STATUS_CANCELLED,
                    'reason' => $event->reason(),
                    'by' => $event->cancelledBy(),
                    'timestamp' => $event->occurredAt()
                ]
            ]),
            $this->requestedAt,
            $this->assignedAt,
            $this->startedAt,
            $this->completedAt,
            $event->occurredAt()
        );
    }
}