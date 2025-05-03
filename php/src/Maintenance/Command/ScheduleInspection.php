<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

final readonly class ScheduleInspection
{
    public function __construct(
        public string $roomId,
        public string $inspectionType,
        public \DateTimeImmutable $scheduledAt
    ) {
    }
}