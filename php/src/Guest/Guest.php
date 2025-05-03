<?php

declare(strict_types=1);

namespace App\Guest;

use App\Guest\DomainEvent\GuestRegistered;
use App\Guest\DomainEvent\GuestProfileUpdated;
use App\Guest\DomainEvent\GuestPreferencesUpdated;

final class Guest
{
    private function __construct(
        private GuestId $id,
        private string $name,
        private string $email,
        private ?string $phone,
        private array $preferences
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
            $this->preferences
        );
    }
    
    public function applyGuestPreferencesUpdated(GuestPreferencesUpdated $event): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $this->phone,
            $event->newPreferences
        );
    }
}