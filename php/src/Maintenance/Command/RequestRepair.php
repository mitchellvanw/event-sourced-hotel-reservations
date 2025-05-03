<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

final readonly class RequestRepair
{
    public function __construct(
        public string $roomId,
        public string $issue,
        public ?string $priority = 'normal'
    ) {
    }
}