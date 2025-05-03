<?php

declare(strict_types=1);

namespace App\Finance;

final readonly class InvoiceId
{
    private function __construct(
        private string $id
    ) {
    }

    public static function generate(): self
    {
        return new self(uniqid('invoice_'));
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