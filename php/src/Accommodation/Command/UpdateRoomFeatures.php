<?php

namespace Accommodation\Command;

class UpdateRoomFeatures
{
    public function __construct(
        public readonly string $roomId,
        public readonly array $features,
        public readonly array $removeFeatures = [],
        public readonly ?string $updatedBy = null
    ) {
    }
}