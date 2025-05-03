<?php

namespace Accommodation\Query;

class GetBlockedRooms
{
    public function __construct(
        public readonly ?\DateTimeImmutable $forDate = null,
        public readonly ?string $reason = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}