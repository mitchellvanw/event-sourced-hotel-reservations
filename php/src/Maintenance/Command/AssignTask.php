<?php

namespace Maintenance\Command;

class AssignTask
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $taskType,
        public readonly string $assignedTo,
        public readonly ?string $notes = null
    ) {
    }
}