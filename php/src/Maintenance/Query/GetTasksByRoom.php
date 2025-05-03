<?php

namespace Maintenance\Query;

class GetTasksByRoom
{
    public function __construct(
        public readonly string $roomId,
        public readonly ?string $status = null,
        public readonly ?string $taskType = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}