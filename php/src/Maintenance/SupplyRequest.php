<?php

declare(strict_types=1);

namespace Maintenance;

use Maintenance\DomainEvent\MaintenanceSuppliesRequested;
use Maintenance\DomainEvent\MaintenanceSuppliesReceived;

final class SupplyRequest
{
    private function __construct(
        private SupplyRequestId $id,
        private string $taskId,
        private array $supplies,
        private array $receivedSupplies = [],
        private bool $fulfilled = false,
        private ?string $requestedBy = null,
        private ?string $notes = null,
        private ?string $urgency = null,
        private ?string $receivedBy = null,
        private ?\DateTimeImmutable $requestedAt = null,
        private ?\DateTimeImmutable $fulfilledAt = null
    ) {
    }
    
    public static function request(
        SupplyRequestId $id,
        string $taskId,
        array $supplies,
        ?string $requestedBy = null,
        ?string $notes = null,
        ?string $urgency = null
    ): array {
        if (empty($supplies)) {
            throw new \InvalidArgumentException('Supply request must contain at least one item');
        }
        
        // Validate each supply item has the necessary information
        foreach ($supplies as $item) {
            if (!isset($item['name']) || empty($item['name'])) {
                throw new \InvalidArgumentException('Each supply item must have a name');
            }
            
            if (!isset($item['quantity']) || !is_numeric($item['quantity']) || $item['quantity'] <= 0) {
                throw new \InvalidArgumentException('Each supply item must have a valid positive quantity');
            }
        }
        
        $event = new MaintenanceSuppliesRequested(
            $id->toString(),
            $taskId,
            $supplies,
            $requestedBy,
            $notes,
            $urgency,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function receiveSupplies(
        array $receivedSupplies,
        bool $fullyFulfilled,
        ?string $receivedBy = null,
        ?array $missingItems = null
    ): array {
        if ($this->fulfilled) {
            throw new \DomainException('Supply request has already been fulfilled');
        }
        
        if (empty($receivedSupplies)) {
            throw new \InvalidArgumentException('Received supplies cannot be empty');
        }
        
        // Validate each received item has the necessary information
        foreach ($receivedSupplies as $item) {
            if (!isset($item['name']) || empty($item['name'])) {
                throw new \InvalidArgumentException('Each received item must have a name');
            }
            
            if (!isset($item['quantity']) || !is_numeric($item['quantity']) || $item['quantity'] <= 0) {
                throw new \InvalidArgumentException('Each received item must have a valid positive quantity');
            }
        }
        
        $event = new MaintenanceSuppliesReceived(
            $this->id->toString(),
            $receivedSupplies,
            $fullyFulfilled,
            $receivedBy,
            $missingItems,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyMaintenanceSuppliesRequested(MaintenanceSuppliesRequested $event): self
    {
        return new self(
            SupplyRequestId::fromString($event->requestId()),
            $event->taskId(),
            $event->supplies(),
            [],
            false,
            $event->requestedBy(),
            $event->notes(),
            $event->urgency(),
            null,
            $event->occurredAt(),
            null
        );
    }
    
    public function applyMaintenanceSuppliesReceived(MaintenanceSuppliesReceived $event): self
    {
        return new self(
            $this->id,
            $this->taskId,
            $this->supplies,
            array_merge($this->receivedSupplies, $event->receivedSupplies()),
            $event->fullyFulfilled(),
            $this->requestedBy,
            $this->notes,
            $this->urgency,
            $event->receivedBy(),
            $this->requestedAt,
            $event->fullyFulfilled() ? $event->occurredAt() : null
        );
    }
}