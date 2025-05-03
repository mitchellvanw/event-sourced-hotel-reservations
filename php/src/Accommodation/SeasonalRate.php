<?php

declare(strict_types=1);

namespace Accommodation;

use Accommodation\DomainEvent\SeasonalRateCreated;
use Accommodation\DomainEvent\SeasonalRateUpdated;

final class SeasonalRate
{
    private function __construct(
        private RateId $id,
        private string $roomTypeId,
        private float $rate,
        private string $seasonName,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private ?string $description = null,
        private ?string $createdBy = null,
        private array $priceHistory = []
    ) {
    }
    
    public static function create(
        RateId $id,
        string $roomTypeId,
        float $rate,
        string $seasonName,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $description = null,
        ?string $createdBy = null
    ): array {
        if ($rate <= 0) {
            throw new \InvalidArgumentException('Rate must be positive');
        }
        
        if ($startDate >= $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
        
        $event = new SeasonalRateCreated(
            $id->toString(),
            $roomTypeId,
            $rate,
            $seasonName,
            $startDate,
            $endDate,
            $description,
            $createdBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function update(
        float $newRate = null,
        \DateTimeImmutable $newStartDate = null,
        \DateTimeImmutable $newEndDate = null,
        ?string $reason = null,
        ?string $updatedBy = null
    ): array {
        $hasChanges = false;
        
        if ($newRate !== null && $newRate <= 0) {
            throw new \InvalidArgumentException('Rate must be positive');
        }
        
        if ($newStartDate !== null && $newEndDate !== null && $newStartDate >= $newEndDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
        
        if ($newStartDate !== null && $newEndDate === null && $newStartDate >= $this->endDate) {
            throw new \InvalidArgumentException('New start date must be before existing end date');
        }
        
        if ($newEndDate !== null && $newStartDate === null && $newEndDate <= $this->startDate) {
            throw new \InvalidArgumentException('New end date must be after existing start date');
        }
        
        $oldRate = $this->rate;
        $finalRate = $newRate ?? $oldRate;
        
        $oldStartDate = $this->startDate;
        $finalStartDate = $newStartDate ?? $oldStartDate;
        
        $oldEndDate = $this->endDate;
        $finalEndDate = $newEndDate ?? $oldEndDate;
        
        // Determine if there are any actual changes
        if ($finalRate !== $oldRate) {
            $hasChanges = true;
        }
        
        if ($finalStartDate !== $oldStartDate) {
            $hasChanges = true;
        }
        
        if ($finalEndDate !== $oldEndDate) {
            $hasChanges = true;
        }
        
        if (!$hasChanges) {
            throw new \InvalidArgumentException('No changes provided for update');
        }
        
        $event = new SeasonalRateUpdated(
            $this->id->toString(),
            $this->roomTypeId,
            $oldRate,
            $finalRate,
            $newStartDate !== null ? $oldStartDate : null,
            $newStartDate,
            $newEndDate !== null ? $oldEndDate : null,
            $newEndDate,
            $reason,
            $updatedBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applySeasonalRateCreated(SeasonalRateCreated $event): self
    {
        return new self(
            RateId::fromString($event->rateId()),
            $event->roomTypeId(),
            $event->rate(),
            $event->seasonName(),
            $event->startDate(),
            $event->endDate(),
            $event->description(),
            $event->createdBy(),
            [
                [
                    'rate' => $event->rate(),
                    'timestamp' => $event->occurredAt()
                ]
            ]
        );
    }
    
    public function applySeasonalRateUpdated(SeasonalRateUpdated $event): self
    {
        $startDate = $event->newStartDate() ?? $this->startDate;
        $endDate = $event->newEndDate() ?? $this->endDate;
        
        return new self(
            $this->id,
            $this->roomTypeId,
            $event->newRate(),
            $this->seasonName,
            $startDate,
            $endDate,
            $this->description,
            $this->createdBy,
            array_merge($this->priceHistory, [
                [
                    'oldRate' => $event->oldRate(),
                    'newRate' => $event->newRate(),
                    'reason' => $event->reason(),
                    'updatedBy' => $event->updatedBy(),
                    'timestamp' => $event->occurredAt()
                ]
            ])
        );
    }
}