<?php

declare(strict_types=1);

namespace App\Reservation\DomainEvent;

final readonly class SpecialRequestAdded
{
    public function __construct(
        public string $bookingId,
        public string $requestType,
        public string $requestDetails,
        public ?string $status = 'pending',
        public \DateTimeImmutable $timestamp,
    ) {
    }
}