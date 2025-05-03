<?php

namespace Maintenance\DomainEvent;

class TaskCancelled
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $taskId,
        private string $taskType,
        private string $reason,
        private ?string $cancelledBy = null,
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

    public function cancelledBy(): ?string
    {
        return $this->cancelledBy;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}