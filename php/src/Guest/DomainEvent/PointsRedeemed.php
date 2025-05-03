<?php

declare(strict_types=1);

namespace App\Guest\DomainEvent;

final readonly class PointsRedeemed
{
    public function __construct(
        public string $loyaltyAccountId,
        public int $points,
        public string $redemptionType,
        public ?string $redemptionDescription = null, // e.g., 'Free night', 'Room upgrade', etc.
        public ?string $benefitType = null,  // e.g., 'stay', 'service', 'product', etc.
        public ?string $benefitId = null,    // ID of the benefit (stayId, serviceId, etc.)
        public \DateTimeImmutable $timestamp,
    ) {
    }
}