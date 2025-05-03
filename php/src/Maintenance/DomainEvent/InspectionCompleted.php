<?php

declare(strict_types=1);

namespace App\Maintenance\DomainEvent;

final readonly class InspectionCompleted
{
    public function __construct(
        public string $taskId,
        public string $roomId,
        public string $inspectorId,
        public string $result,
        public ?array $issues = [],
        public \DateTimeImmutable $timestamp,
    ) {
    }
}