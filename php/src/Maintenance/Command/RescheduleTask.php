<?php

namespace Maintenance\Command;

class RescheduleTask
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $taskType,
        public readonly \DateTimeImmutable $newScheduledTime,
        public readonly string $reason,
        public readonly ?string $rescheduledBy = null
    ) {
    }
}