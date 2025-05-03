<?php

declare(strict_types=1);

namespace App\Guest\Command;

final readonly class UpdateGuestProfile
{
    public function __construct(
        public string $guestId,
        public string $name,
        public string $email,
        public ?string $phone = null
    ) {
    }
}