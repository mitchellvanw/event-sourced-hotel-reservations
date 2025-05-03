<?php

declare(strict_types=1);

namespace Accommodation;

use Accommodation\DomainEvent\RoomCreated;
use Accommodation\DomainEvent\RoomStatusChanged;
use Accommodation\DomainEvent\RoomTypeChanged;
use Accommodation\DomainEvent\RoomRateUpdated;
use Accommodation\DomainEvent\RoomFeaturesUpdated;
use Accommodation\DomainEvent\RoomBlocked;
use Accommodation\DomainEvent\RoomBlockReleased;

final class Room
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_OCCUPIED = 'occupied';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_CLEANING = 'cleaning';
    public const STATUS_BLOCKED = 'blocked';
    public const STATUS_OUT_OF_ORDER = 'out_of_order';

    private function __construct(
        private RoomId $id,
        private string $roomNumber,
        private string $type,
        private string $status,
        private float $rate,
        private array $features = [],
        private array $blocks = [],
        private ?\DateTimeImmutable $blockedUntil = null,
        private ?string $blockReason = null,
        private array $statusHistory = []
    ) {
    }
    
    public static function create(
        RoomId $id,
        string $roomNumber,
        string $type,
        float $rate,
        array $features = []
    ): array {
        if ($rate <= 0) {
            throw new \InvalidArgumentException('Rate must be positive');
        }
        
        $event = new RoomCreated(
            $id->toString(),
            $roomNumber,
            $type,
            $rate,
            self::STATUS_AVAILABLE,
            $features,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function changeStatus(string $newStatus): array
    {
        if ($this->status === $newStatus) {
            return [];
        }
        
        if ($this->status === self::STATUS_BLOCKED && $newStatus !== self::STATUS_AVAILABLE) {
            throw new \DomainException('Blocked rooms must be explicitly released before changing to another status');
        }
        
        $event = new RoomStatusChanged(
            $this->id->toString(),
            $this->status,
            $newStatus,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function changeType(string $newType): array
    {
        if ($this->type === $newType) {
            return [];
        }
        
        $event = new RoomTypeChanged(
            $this->id->toString(),
            $this->type,
            $newType,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function updateRate(float $newRate): array
    {
        if ($this->rate === $newRate) {
            return [];
        }
        
        if ($newRate <= 0) {
            throw new \InvalidArgumentException('Rate must be positive');
        }
        
        $event = new RoomRateUpdated(
            $this->id->toString(),
            $this->rate,
            $newRate,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function updateFeatures(array $newFeatures, array $removeFeatures = [], ?string $updatedBy = null): array
    {
        if (empty($newFeatures) && empty($removeFeatures)) {
            return [];
        }
        
        $event = new RoomFeaturesUpdated(
            $this->id->toString(),
            $newFeatures,
            $removeFeatures,
            $updatedBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function block(
        string $reason,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $notes = null,
        ?string $blockedBy = null
    ): array {
        if ($this->status === self::STATUS_BLOCKED) {
            throw new \DomainException('Room is already blocked');
        }
        
        if ($startDate >= $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
        
        $event = new RoomBlocked(
            $this->id->toString(),
            $reason,
            $startDate,
            $endDate,
            $notes,
            $blockedBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function releaseBlock(string $reason, ?string $releasedBy = null): array
    {
        if ($this->status !== self::STATUS_BLOCKED) {
            throw new \DomainException('Room is not blocked');
        }
        
        $event = new RoomBlockReleased(
            $this->id->toString(),
            $reason,
            $releasedBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyRoomCreated(RoomCreated $event): self
    {
        return new self(
            RoomId::fromString($event->roomId()),
            $event->roomNumber(),
            $event->type(),
            $event->status(),
            $event->rate(),
            $event->features() ?? [],
            [],
            null,
            null,
            [
                [
                    'status' => $event->status(),
                    'timestamp' => $event->occurredAt()
                ]
            ]
        );
    }
    
    public function applyRoomStatusChanged(RoomStatusChanged $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $this->type,
            $event->newStatus(),
            $this->rate,
            $this->features,
            $this->blocks,
            $this->blockedUntil,
            $this->blockReason,
            array_merge($this->statusHistory, [
                [
                    'oldStatus' => $event->oldStatus(),
                    'newStatus' => $event->newStatus(),
                    'timestamp' => $event->occurredAt()
                ]
            ])
        );
    }
    
    public function applyRoomTypeChanged(RoomTypeChanged $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $event->newType(),
            $this->status,
            $this->rate,
            $this->features,
            $this->blocks,
            $this->blockedUntil,
            $this->blockReason,
            $this->statusHistory
        );
    }
    
    public function applyRoomRateUpdated(RoomRateUpdated $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $this->type,
            $this->status,
            $event->newRate(),
            $this->features,
            $this->blocks,
            $this->blockedUntil,
            $this->blockReason,
            $this->statusHistory
        );
    }
    
    public function applyRoomFeaturesUpdated(RoomFeaturesUpdated $event): self
    {
        // Add new features
        $updatedFeatures = $this->features;
        
        foreach ($event->features() as $feature) {
            if (!in_array($feature, $updatedFeatures)) {
                $updatedFeatures[] = $feature;
            }
        }
        
        // Remove features if requested
        if (!empty($event->removedFeatures())) {
            $updatedFeatures = array_filter($updatedFeatures, function ($feature) use ($event) {
                return !in_array($feature, $event->removedFeatures());
            });
        }
        
        return new self(
            $this->id,
            $this->roomNumber,
            $this->type,
            $this->status,
            $this->rate,
            $updatedFeatures,
            $this->blocks,
            $this->blockedUntil,
            $this->blockReason,
            $this->statusHistory
        );
    }
    
    public function applyRoomBlocked(RoomBlocked $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $this->type,
            self::STATUS_BLOCKED,
            $this->rate,
            $this->features,
            array_merge($this->blocks, [
                [
                    'reason' => $event->reason(),
                    'startDate' => $event->startDate(),
                    'endDate' => $event->endDate(),
                    'notes' => $event->notes(),
                    'blockedBy' => $event->blockedBy(),
                    'blockedAt' => $event->occurredAt()
                ]
            ]),
            $event->endDate(),
            $event->reason(),
            array_merge($this->statusHistory, [
                [
                    'oldStatus' => $this->status,
                    'newStatus' => self::STATUS_BLOCKED,
                    'reason' => $event->reason(),
                    'timestamp' => $event->occurredAt()
                ]
            ])
        );
    }
    
    public function applyRoomBlockReleased(RoomBlockReleased $event): self
    {
        return new self(
            $this->id,
            $this->roomNumber,
            $this->type,
            self::STATUS_AVAILABLE,
            $this->rate,
            $this->features,
            array_merge($this->blocks, [
                [
                    'releasedAt' => $event->occurredAt(),
                    'releaseReason' => $event->reason(),
                    'releasedBy' => $event->releasedBy()
                ]
            ]),
            null,
            null,
            array_merge($this->statusHistory, [
                [
                    'oldStatus' => self::STATUS_BLOCKED,
                    'newStatus' => self::STATUS_AVAILABLE,
                    'reason' => $event->reason(),
                    'timestamp' => $event->occurredAt()
                ]
            ])
        );
    }
}