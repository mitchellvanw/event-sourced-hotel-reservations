<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

final readonly class CompleteInspection
{
    public function __construct(
        public string $taskId,
        public string $inspectorId,
        public string $result,
        public array $issues = []
    ) {
    }
}