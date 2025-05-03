<?php

declare(strict_types=1);

namespace App\Guest;

use App\Guest\DomainEvent\LoyaltyAccountCreated;
use App\Guest\DomainEvent\PointsEarned;
use App\Guest\DomainEvent\PointsRedeemed;
use App\Guest\DomainEvent\LoyaltyTierChanged;
use App\Guest\DomainEvent\LoyaltyAccountLinked;

final class LoyaltyAccount
{
    private function __construct(
        private LoyaltyAccountId $id,
        private GuestId $guestId,
        private string $tier,
        private int $points,
        private array $pointsHistory = [],
        private array $redemptionHistory = []
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
        
        // Make sure we also link the loyalty account to the guest
        $linkEvent = new LoyaltyAccountLinked(
            $guestId->toString(),
            $id->toString(),
            new \DateTimeImmutable()
        );
        
        return [$event, $linkEvent];
    }
    
    public function earnPoints(
        int $points, 
        string $reason, 
        ?string $sourceType = null, 
        ?string $sourceId = null, 
        ?\DateTimeImmutable $expiresAt = null
    ): array {
        if ($points <= 0) {
            throw new \InvalidArgumentException('Points must be a positive number');
        }
        
        $event = new PointsEarned(
            $this->id->toString(),
            $points,
            $reason,
            $sourceType,
            $sourceId,
            $expiresAt,
            new \DateTimeImmutable()
        );

        $events = [$event];
        
        // Check if new tier level is reached
        $newTier = $this->calculateTier($this->points + $points);
        if ($newTier !== $this->tier) {
            $events[] = new LoyaltyTierChanged(
                $this->id->toString(),
                $this->tier,
                $newTier,
                'Points threshold reached',
                new \DateTimeImmutable()
            );
        }
        
        return $events;
    }
    
    public function redeemPoints(
        int $points, 
        string $redemptionType, 
        ?string $redemptionDescription = null,
        ?string $benefitType = null,
        ?string $benefitId = null
    ): array {
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
            $redemptionDescription,
            $benefitType,
            $benefitId,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function changeTier(string $newTier, ?string $reason = null): array
    {
        if ($this->tier === $newTier) {
            return [];
        }
        
        $event = new LoyaltyTierChanged(
            $this->id->toString(),
            $this->tier,
            $newTier,
            $reason,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    private function calculateTier(int $points): string
    {
        // This is a simple example logic - in real life, tier calculations
        // could be much more complex based on points, stay history, etc.
        if ($points >= 50000) {
            return 'platinum';
        } elseif ($points >= 25000) {
            return 'gold';
        } elseif ($points >= 10000) {
            return 'silver';
        } else {
            return 'standard';
        }
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
        $pointsHistory = $this->pointsHistory;
        $pointsHistory[] = [
            'amount' => $event->points,
            'reason' => $event->reason,
            'sourceType' => $event->sourceType,
            'sourceId' => $event->sourceId,
            'expiresAt' => $event->expiresAt,
            'timestamp' => $event->timestamp
        ];
        
        return new self(
            $this->id,
            $this->guestId,
            $this->tier,
            $this->points + $event->points,
            $pointsHistory,
            $this->redemptionHistory
        );
    }
    
    public function applyPointsRedeemed(PointsRedeemed $event): self
    {
        $redemptionHistory = $this->redemptionHistory;
        $redemptionHistory[] = [
            'amount' => $event->points,
            'redemptionType' => $event->redemptionType,
            'redemptionDescription' => $event->redemptionDescription,
            'benefitType' => $event->benefitType,
            'benefitId' => $event->benefitId,
            'timestamp' => $event->timestamp
        ];
        
        return new self(
            $this->id,
            $this->guestId,
            $this->tier,
            $this->points - $event->points,
            $this->pointsHistory,
            $redemptionHistory
        );
    }
    
    public function applyLoyaltyTierChanged(LoyaltyTierChanged $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $event->newTier,
            $this->points,
            $this->pointsHistory,
            $this->redemptionHistory
        );
    }
}