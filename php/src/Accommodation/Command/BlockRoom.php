<?php

namespace Accommodation\Command;

class BlockRoom
{
    public function __construct(
        public readonly string $roomId,
        public readonly string $reason,
        public readonly \DateTimeImmutable $startDate,
        public readonly \DateTimeImmutable $endDate,
        public readonly ?string $notes = null,
        public readonly ?string $blockedBy = null
    ) {
    }
}