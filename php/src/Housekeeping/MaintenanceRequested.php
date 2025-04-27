<?php

declare(strict_types=1);

namespace App\Housekeeping;

final readonly class MaintenanceRequested
{
    public function __construct(
        public string $requestId,
        public string $roomId,
        public string $issue,
        public \DateTimeImmutable $timestamp,
    ) {
    }
}