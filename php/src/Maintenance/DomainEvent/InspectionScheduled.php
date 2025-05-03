<?php

declare(strict_types=1);

namespace App\Maintenance\DomainEvent;

final readonly class InspectionScheduled
{
    public function __construct(
        public string $taskId,
        public string $roomId,
        public string $inspectionType,
        public \DateTimeImmutable $scheduledAt,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}