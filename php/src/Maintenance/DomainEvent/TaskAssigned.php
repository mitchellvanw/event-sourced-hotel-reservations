<?php

namespace Maintenance\DomainEvent;

class TaskAssigned
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $taskId,
        private string $taskType,
        private string $assignedTo,
        private ?string $notes = null,
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

    public function assignedTo(): string
    {
        return $this->assignedTo;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}