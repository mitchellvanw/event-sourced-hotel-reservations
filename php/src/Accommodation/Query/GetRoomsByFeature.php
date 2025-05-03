<?php

namespace Accommodation\Query;

class GetRoomsByFeature
{
    public function __construct(
        public readonly string $feature,
        public readonly ?string $status = null,
        public readonly ?string $roomType = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null
    ) {
    }
}