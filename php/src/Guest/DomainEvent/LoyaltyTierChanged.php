<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class LoyaltyTierChanged
{
    public function __construct(
        public string $loyaltyAccountId,
        public string $oldTier,
        public string $newTier,
        public ?string $reason = null,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}