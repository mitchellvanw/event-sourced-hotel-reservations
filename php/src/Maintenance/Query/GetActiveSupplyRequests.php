<?php

namespace Maintenance\Query;

class GetActiveSupplyRequests
{
    public function __construct(
        public readonly ?string $urgency = null,
        public readonly ?string $taskId = null,
        public readonly ?bool $onlyUnfulfilled = true,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}