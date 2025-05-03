<?php

declare(strict_types=1);

namespace App\Maintenance\DomainEvent;

final readonly class CleaningCompleted
{
    public function __construct(
        public string $taskId,
        public string $roomId,
        public string $staffId,
        public ?string $notes = null,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}