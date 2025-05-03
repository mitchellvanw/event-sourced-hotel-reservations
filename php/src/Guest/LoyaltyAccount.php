<?php

declare(strict_types=1);

namespace App\Guest;

use App\Guest\DomainEvent\LoyaltyAccountCreated;
use App\Guest\DomainEvent\PointsEarned;
use App\Guest\DomainEvent\PointsRedeemed;

final class LoyaltyAccount
{
    private function __construct(
        private LoyaltyAccountId $id,
        private GuestId $guestId,
        private string $tier,
        private int $points
    ) {
    }
    
    public static function create(
        LoyaltyAccountId $id,
        GuestId $guestId,
        string $tier = 'standard'
    ): array {
        $event = new LoyaltyAccountCreated(
            $id->toString(),
            $guestId->toString(),
            $tier,
            0,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function earnPoints(int $points, string $reason): array
    {
        if ($points <= 0) {
            throw new \InvalidArgumentException('Points must be a positive number');
        }
        
        $event = new PointsEarned(
            $this->id->toString(),
            $points,
            $reason,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function redeemPoints(int $points, string $redemptionType): array
    {
        if ($points <= 0) {
            throw new \InvalidArgumentException('Points must be a positive number');
        }
        
        if ($this->points < $points) {
            throw new \DomainException('Insufficient points for redemption');
        }
        
        $event = new PointsRedeemed(
            $this->id->toString(),
            $points,
            $redemptionType,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyLoyaltyAccountCreated(LoyaltyAccountCreated $event): self
    {
        return new self(
            LoyaltyAccountId::fromString($event->loyaltyAccountId),
            GuestId::fromString($event->guestId),
            $event->tier,
            $event->points
        );
    }
    
    public function applyPointsEarned(PointsEarned $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->tier,
            $this->points + $event->points
        );
    }
    
    public function applyPointsRedeemed(PointsRedeemed $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->tier,
            $this->points - $event->points
        );
    }
}