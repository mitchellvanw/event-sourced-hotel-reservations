<?php

namespace Maintenance\Command;

class ReceiveSupplies
{
    public function __construct(
        public readonly string $requestId,
        public readonly array $receivedSupplies,
        public readonly bool $fullyFulfilled,
        public readonly ?string $receivedBy = null,
        public readonly ?array $missingItems = null
    ) {
    }
}