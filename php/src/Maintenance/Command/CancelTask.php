<?php

namespace Maintenance\Command;

class CancelTask
{
    public function __construct(
        public readonly string $taskId,
        public readonly string $taskType,
        public readonly string $reason,
        public readonly ?string $cancelledBy = null
    ) {
    }
}