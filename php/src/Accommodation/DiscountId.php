<?php

namespace Accommodation;

class DiscountId
{
    private string $id;

    public function __construct(?string $id = null)
    {
        $this->id = $id ?? (string) \Ramsey\Uuid\Uuid::uuid4();
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }
}