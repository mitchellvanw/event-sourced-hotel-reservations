<?php

declare(strict_types=1);

namespace App\Maintenance\Query;

final readonly class GetPendingTasks
{
    public function __construct(
        public ?string $taskType = null,
        public ?string $roomId = null,
        public ?string $priority = null,
        public ?int $limit = 10,
        public ?int $offset = 0
    ) {
    }
}