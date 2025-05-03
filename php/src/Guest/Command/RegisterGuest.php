<?php

declare(strict_types=1);

namespace App\Guest\Command;

final readonly class RegisterGuest
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone = null,
        public array $preferences = []
    ) {
    }
}