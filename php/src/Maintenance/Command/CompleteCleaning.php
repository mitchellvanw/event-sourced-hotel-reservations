<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

final readonly class CompleteCleaning
{
    public function __construct(
        public string $taskId,
        public string $staffId,
        public ?string $notes = null
    ) {
    }
}