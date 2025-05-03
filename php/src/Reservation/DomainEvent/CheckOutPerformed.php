<?php

declare(strict_types=1);

namespace App\Reservation\DomainEvent;

final readonly class CheckOutPerformed
{
    public function __construct(
        public string $id,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}