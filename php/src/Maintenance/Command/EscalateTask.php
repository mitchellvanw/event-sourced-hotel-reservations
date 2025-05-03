<?php

namespace Maintenance\Command;

class EscalateTask
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $taskType,
        public readonly string $reason,
        public readonly int $severityLevel,
        public readonly ?string $escalatedBy = null,
        public readonly ?string $notes = null
    ) {
    }
}