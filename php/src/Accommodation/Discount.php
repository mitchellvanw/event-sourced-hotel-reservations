<?php

declare(strict_types=1);

namespace Accommodation;

use Accommodation\DomainEvent\RoomDiscountApplied;

final class Discount
{
    private function __construct(
        private DiscountId $id,
        private string $roomTypeId,
        private float $discountPercentage,
        private string $discountName,
        private string $discountCode,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private ?string $appliedBy = null
    ) {
    }
    
    public static function apply(
        DiscountId $id,
        string $roomTypeId,
        float $discountPercentage,
        string $discountName,
        string $discountCode,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $appliedBy = null
    ): array {
        if ($discountPercentage <= 0 || $discountPercentage > 100) {
            throw new \InvalidArgumentException('Discount percentage must be between 0 and 100');
        }
        
        if ($startDate >= $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
        
        if (empty($discountCode)) {
            throw new \InvalidArgumentException('Discount code cannot be empty');
        }
        
        $event = new RoomDiscountApplied(
            $id->toString(),
            $roomTypeId,
            $discountPercentage,
            $discountName,
            $discountCode,
            $startDate,
            $endDate,
            $appliedBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyRoomDiscountApplied(RoomDiscountApplied $event): self
    {
        return new self(
            DiscountId::fromString($event->discountId()),
            $event->roomTypeId(),
            $event->discountPercentage(),
            $event->discountName(),
            $event->discountCode(),
            $event->startDate(),
            $event->endDate(),
            $event->appliedBy()
        );
    }
}