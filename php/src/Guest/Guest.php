<?php

declare(strict_types=1);

namespace App\Guest;

final readonly class Guest
{
    private string $guestId;
    private string $name;
    private string $email;
    private ?string $phone;
    private array $preferences;
    
    private function __construct(string $guestId)
    {
        $this->guestId = $guestId;
    }
    
    public static function register(
        string $guestId,
        string $name,
        string $email,
        ?string $phone = null,
        array $preferences = []
    ): array {
        $guest = new self($guestId);
        $event = new GuestRegistered(
            guestId: $guestId,
            name: $name,
            email: $email,
            phone: $phone,
            preferences: $preferences,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$guest->applyGuestRegistered($event), $event];
    }
    
    public function updateProfile(string $name, string $email, ?string $phone): array
    {
        if ($this->name === $name && $this->email === $email && $this->phone === $phone) {
            return [$this, null];
        }
        
        $event = new GuestProfileUpdated(
            guestId: $this->guestId,
            name: $name,
            email: $email,
            phone: $phone,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyGuestProfileUpdated($event), $event];
    }
    
    public function changePreferences(array $preferences): array
    {
        if ($this->preferences === $preferences) {
            return [$this, null];
        }
        
        $event = new GuestPreferencesChanged(
            guestId: $this->guestId,
            oldPreferences: $this->preferences,
            newPreferences: $preferences,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyGuestPreferencesChanged($event), $event];
    }
    
    public function applyGuestRegistered(GuestRegistered $event): self
    {
        $guest = clone $this;
        $guest->name = $event->name;
        $guest->email = $event->email;
        $guest->phone = $event->phone;
        $guest->preferences = $event->preferences;
        
        return $guest;
    }
    
    public function applyGuestProfileUpdated(GuestProfileUpdated $event): self
    {
        $guest = clone $this;
        $guest->name = $event->name;
        $guest->email = $event->email;
        $guest->phone = $event->phone;
        
        return $guest;
    }
    
    public function applyGuestPreferencesChanged(GuestPreferencesChanged $event): self
    {
        $guest = clone $this;
        $guest->preferences = $event->newPreferences;
        
        return $guest;
    }
}