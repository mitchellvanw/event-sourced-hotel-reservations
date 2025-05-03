<?php

declare(strict_types=1);

namespace App\Accommodation\Query;

final readonly class GetRoomById
{
    public function __construct(
        public string $roomId
    ) {
    }
}