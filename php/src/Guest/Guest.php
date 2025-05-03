<?php

declare(strict_types=1);

namespace App\Guest;

use App\Guest\DomainEvent\GuestRegistered;
use App\Guest\DomainEvent\GuestProfileUpdated;
use App\Guest\DomainEvent\GuestPreferencesUpdated;
use App\Guest\DomainEvent\GuestAccountDeactivated;
use App\Guest\DomainEvent\GuestAccountReactivated;
use App\Guest\DomainEvent\LoyaltyAccountLinked;
use App\Guest\DomainEvent\GuestMerged;

final class Guest
{
    private function __construct(
        private GuestId $id,
        private string $name,
        private string $email,
        private ?string $phone,
        private array $preferences,
        private bool $isActive = true,
        private ?LoyaltyAccountId $loyaltyAccountId = null,
        private ?string $deactivationReason = null,
        private array $mergedGuestIds = [],
    ) {
    }
    
    public static function register(
        GuestId $id,
        string $name,
        string $email,
        ?string $phone = null,
        array $preferences = []
    ): array {
        $event = new GuestRegistered(
            $id->toString(),
            $name,
            $email,
            $phone,
            $preferences,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function updateProfile(string $name, string $email, ?string $phone): array
    {
        if (!$this->isActive) {
            throw new \DomainException('Cannot update profile for a deactivated account');
        }

        if ($this->name === $name && $this->email === $email && $this->phone === $phone) {
            return [];
        }
        
        $event = new GuestProfileUpdated(
            $this->id->toString(),
            $name,
            $email,
            $phone,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function updatePreferences(array $preferences): array
    {
        if (!$this->isActive) {
            throw new \DomainException('Cannot update preferences for a deactivated account');
        }

        if ($this->preferences === $preferences) {
            return [];
        }
        
        $event = new GuestPreferencesUpdated(
            $this->id->toString(),
            $this->preferences,
            $preferences,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }

    public function deactivate(string $reason, ?string $deactivatedBy = null): array
    {
        if (!$this->isActive) {
            throw new \DomainException('Guest account is already deactivated');
        }

        $event = new GuestAccountDeactivated(
            $this->id->toString(),
            $reason,
            $deactivatedBy,
            new \DateTimeImmutable()
        );

        return [$event];
    }

    public function reactivate(?string $reactivatedBy = null, ?string $notes = null): array
    {
        if ($this->isActive) {
            throw new \DomainException('Guest account is already active');
        }

        $event = new GuestAccountReactivated(
            $this->id->toString(),
            $reactivatedBy,
            $notes,
            new \DateTimeImmutable()
        );

        return [$event];
    }

    public function linkLoyaltyAccount(LoyaltyAccountId $loyaltyAccountId): array
    {
        if (!$this->isActive) {
            throw new \DomainException('Cannot link loyalty account to a deactivated guest account');
        }

        if ($this->loyaltyAccountId !== null) {
            throw new \DomainException('Guest already has a linked loyalty account');
        }

        $event = new LoyaltyAccountLinked(
            $this->id->toString(),
            $loyaltyAccountId->toString(),
            new \DateTimeImmutable()
        );

        return [$event];
    }

    public function mergeGuest(GuestId $secondaryGuestId, ?string $mergedBy = null, ?string $notes = null): array
    {
        if (!$this->isActive) {
            throw new \DomainException('Cannot merge into a deactivated guest account');
        }

        if ($this->id->toString() === $secondaryGuestId->toString()) {
            throw new \InvalidArgumentException('Cannot merge a guest with itself');
        }

        if (in_array($secondaryGuestId->toString(), $this->mergedGuestIds)) {
            throw new \DomainException('Guest has already been merged');
        }

        $event = new GuestMerged(
            $this->id->toString(),
            $secondaryGuestId->toString(),
            $mergedBy,
            $notes,
            new \DateTimeImmutable()
        );

        return [$event];
    }
    
    public function applyGuestRegistered(GuestRegistered $event): self
    {
        return new self(
            GuestId::fromString($event->guestId),
            $event->name,
            $event->email,
            $event->phone,
            $event->preferences
        );
    }
    
    public function applyGuestProfileUpdated(GuestProfileUpdated $event): self
    {
        return new self(
            $this->id,
            $event->name,
            $event->email,
            $event->phone,
            $this->preferences,
            $this->isActive,
            $this->loyaltyAccountId,
            $this->deactivationReason,
            $this->mergedGuestIds
        );
    }
    
    public function applyGuestPreferencesUpdated(GuestPreferencesUpdated $event): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->phone,
            $event->newPreferences,
            $this->isActive,
            $this->loyaltyAccountId,
            $this->deactivationReason,
            $this->mergedGuestIds
        );
    }

    public function applyGuestAccountDeactivated(GuestAccountDeactivated $event): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->phone,
            $this->preferences,
            false,
            $this->loyaltyAccountId,
            $event->reason,
            $this->mergedGuestIds
        );
    }

    public function applyGuestAccountReactivated(GuestAccountReactivated $event): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->phone,
            $this->preferences,
            true,
            $this->loyaltyAccountId,
            null,
            $this->mergedGuestIds
        );
    }

    public function applyLoyaltyAccountLinked(LoyaltyAccountLinked $event): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->phone,
            $this->preferences,
            $this->isActive,
            LoyaltyAccountId::fromString($event->loyaltyAccountId),
            $this->deactivationReason,
            $this->mergedGuestIds
        );
    }

    public function applyGuestMerged(GuestMerged $event): self
    {
        $mergedIds = $this->mergedGuestIds;
        $mergedIds[] = $event->secondaryGuestId;

        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->phone,
            $this->preferences,
            $this->isActive,
            $this->loyaltyAccountId,
            $this->deactivationReason,
            $mergedIds
        );
    }
}