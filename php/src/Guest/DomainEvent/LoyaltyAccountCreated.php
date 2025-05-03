<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class LoyaltyAccountCreated
{
    public function __construct(
        public string $loyaltyAccountId,
        public string $guestId,
        public string $tier,
        public int $points,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}