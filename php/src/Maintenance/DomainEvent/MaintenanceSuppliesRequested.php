<?php

namespace Maintenance\DomainEvent;

class MaintenanceSuppliesRequested
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $requestId,
        private string $taskId,
        private array $supplies,
        private ?string $requestedBy = null,
        private ?string $notes = null,
        private ?string $urgency = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function requestId(): string
    {
        return $this->requestId;
    }

    public function taskId(): string
    {
        return $this->taskId;
    }

    public function supplies(): array
    {
        return $this->supplies;
    }

    public function requestedBy(): ?string
    {
        return $this->requestedBy;
    }

    public function notes(): ?string
    {
        return $this->notes;
    }

    public function urgency(): ?string
    {
        return $this->urgency;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}