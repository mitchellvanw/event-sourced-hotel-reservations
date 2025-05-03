<?php

namespace Maintenance\Query;

class GetEscalatedTasks
{
    public function __construct(
        public readonly ?int $minimumSeverityLevel = null,
        public readonly ?string $taskType = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}