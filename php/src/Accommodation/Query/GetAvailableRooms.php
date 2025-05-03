<?php

declare(strict_types=1);

namespace App\Accommodation\Query;

final readonly class GetAvailableRooms
{
    public function __construct(
        public ?\DateTimeImmutable $fromDate = null,
        public ?\DateTimeImmutable $toDate = null,
        public ?string $type = null
    ) {
    }
}