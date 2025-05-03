<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class PointsEarned
{
    public function __construct(
        public string $loyaltyAccountId,
        public int $points,
        public string $reason,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}