<?php

declare(strict_types=1);

namespace App\Maintenance\DomainEvent;

final readonly class MaintenanceRequested
{
    public function __construct(
        public string $taskId,
        public string $roomId,
        public string $issue,
        public ?string $priority = 'normal',
        public \DateTimeImmutable $timestamp,
    ) {
    }
}