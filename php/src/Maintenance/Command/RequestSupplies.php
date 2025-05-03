<?php

namespace Maintenance\Command;

class RequestSupplies
{
    public function __construct(
        public readonly string $taskId,
        public readonly array $supplies,
        public readonly ?string $requestedBy = null,
        public readonly ?string $notes = null,
        public readonly ?string $urgency = null
    ) {
    }
}