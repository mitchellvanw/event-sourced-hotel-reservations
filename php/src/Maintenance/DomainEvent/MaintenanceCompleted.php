<?php

declare(strict_types=1);

namespace App\Maintenance\DomainEvent;

final readonly class MaintenanceCompleted
{
    public function __construct(
        public string $taskId,
        public string $roomId,
        public string $staffId,
        public string $resolution,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}