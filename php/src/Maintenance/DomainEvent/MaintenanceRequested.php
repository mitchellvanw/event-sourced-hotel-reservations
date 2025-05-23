<?php

namespace Maintenance\DomainEvent;

class MaintenanceRequested
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $taskId,
        private string $roomId,
        private string $issue,
        private string $priority,
        private ?\DateTimeImmutable $scheduledFor = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function taskId(): string
    {
        return $this->taskId;
    }

    public function roomId(): string
    {
        return $this->roomId;
    }

    public function issue(): string
    {
        return $this->issue;
    }

    public function priority(): string
    {
        return $this->priority;
    }

    public function scheduledFor(): ?\DateTimeImmutable
    {
        return $this->scheduledFor;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}