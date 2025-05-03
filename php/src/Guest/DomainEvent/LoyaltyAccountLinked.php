<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class LoyaltyAccountLinked
{
    public function __construct(
        public string $guestId,
        public string $loyaltyAccountId,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}