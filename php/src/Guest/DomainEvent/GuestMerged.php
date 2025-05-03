<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class GuestMerged
{
    public function __construct(
        public string $primaryGuestId,
        public string $secondaryGuestId,
        public ?string $mergedBy = null,
        public ?string $notes = null,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}