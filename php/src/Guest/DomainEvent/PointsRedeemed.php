<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class PointsRedeemed
{
    public function __construct(
        public string $loyaltyAccountId,
        public int $points,
        public string $redemptionType,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}