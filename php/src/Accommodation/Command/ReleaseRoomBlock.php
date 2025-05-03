<?php

namespace Accommodation\Command;

class ReleaseRoomBlock
{
    public function __construct(
        public readonly string $roomId,
        public readonly string $reason,
        public readonly ?string $releasedBy = null
    ) {
    }
}