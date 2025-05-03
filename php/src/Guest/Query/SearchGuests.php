<?php

declare(strict_types=1);

namespace App\Guest\Query;

final readonly class SearchGuests
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?array $preferences = null,
        public ?int $limit = 10,
        public ?int $offset = 0
    ) {
    }
}