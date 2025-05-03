<?php

namespace Maintenance\DomainEvent;

class TaskEscalated
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $taskId,
        private string $taskType,
        private string $reason,
        private int $severityLevel,
        private ?string $escalatedBy = null,
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

    public function reason(): string
    {
        return $this->reason;
    }

    public function severityLevel(): int
    {
        return $this->severityLevel;
    }

    public function escalatedBy(): ?string
    {
        return $this->escalatedBy;
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