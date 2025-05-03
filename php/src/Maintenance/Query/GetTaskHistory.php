<?php

declare(strict_types=1);

namespace App\Maintenance\Query;

final readonly class GetTaskHistory
{
    public function __construct(
        public ?string $roomId = null,
        public ?string $taskType = null,
        public ?\DateTimeImmutable $startDate = null,
        public ?\DateTimeImmutable $endDate = null,
        public ?string $status = null,
        public ?int $limit = 10,
        public ?int $offset = 0
    ) {
    }
}