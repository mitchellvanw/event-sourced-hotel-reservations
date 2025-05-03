<?php

namespace Maintenance\DomainEvent;

class MaintenanceSuppliesReceived
{
    private \DateTimeImmutable $occurredAt;

    public function __construct(
        private string $requestId,
        private array $receivedSupplies,
        private bool $fullyFulfilled,
        private ?string $receivedBy = null,
        private ?array $missingItems = null,
        ?\DateTimeImmutable $occurredAt = null
    ) {
        $this->occurredAt = $occurredAt ?? new \DateTimeImmutable();
    }

    public function requestId(): string
    {
        return $this->requestId;
    }

    public function receivedSupplies(): array
    {
        return $this->receivedSupplies;
    }

    public function fullyFulfilled(): bool
    {
        return $this->fullyFulfilled;
    }

    public function receivedBy(): ?string
    {
        return $this->receivedBy;
    }

    public function missingItems(): ?array
    {
        return $this->missingItems;
    }

    public function occurredAt(): \DateTimeImmutable
    {
        return $this->occurredAt;
    }
}