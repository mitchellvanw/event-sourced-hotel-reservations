<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class PointsEarned
{
    public function __construct(
        public string $loyaltyAccountId,
        public int $points,
        public string $reason,
        public ?string $sourceType = null,  // e.g., 'stay', 'booking', 'promotion', etc.
        public ?string $sourceId = null,    // ID related to the sourceType (stayId, bookingId, etc.)
        public ?\DateTimeImmutable $expiresAt = null,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}