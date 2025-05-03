<?php

namespace Maintenance\DomainEvent;

class TaskRescheduled
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $taskId,
        private string $taskType,
        private \DateTimeImmutable $oldScheduledTime,
        private \DateTimeImmutable $newScheduledTime,
        private string $reason,
        private ?string $rescheduledBy = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function taskId(): string
    {
        return $this->taskId;
    }

    public function taskType(): string
    {
        return $this->taskType;
    }

    public function oldScheduledTime(): \DateTimeImmutable
    {
        return $this->oldScheduledTime;
    }

    public function newScheduledTime(): \DateTimeImmutable
    {
        return $this->newScheduledTime;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function rescheduledBy(): ?string
    {
        return $this->rescheduledBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}