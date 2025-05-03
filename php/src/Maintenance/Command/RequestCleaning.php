<?php

declare(strict_types=1);

namespace App\Maintenance\Command;

final readonly class RequestCleaning
{
    public function __construct(
        public string $roomId,
        public ?string $priority = 'normal'
    ) {
    }
}