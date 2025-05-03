<?php

namespace Maintenance\Query;

class GetTasksByStatus
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $taskType = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}