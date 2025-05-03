<?php

declare(strict_types=1);

namespace App\Guest;

final readonly class GuestId
{
    private function __construct(
        private string $id
    ) {
    }

    public static function generate(): self
    {
        return new self(uniqid('guest_'));
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}