<?php

declare(strict_types=1);

namespace App\Reservation;

use App\Reservation\DomainEvent\CheckInPerformed;
use App\Reservation\DomainEvent\CheckOutPerformed;

final class Stay
{
    private function __construct(
        private StayId $id,
        private BookingId $bookingId,
        private string $guestId,
        private string $roomId,
        private \DateTimeImmutable $checkInDate,
        private \DateTimeImmutable $checkOutDate,
        private string $status = 'active',
        private \DateTimeImmutable $checkedInAt,
        private ?\DateTimeImmutable $checkedOutAt = null,
    ) {
    }

    public static function fromBooking(
        StayId $id,
        BookingId $bookingId,
        string $guestId,
        string $roomId,
        \DateTimeImmutable $checkInDate,
        \DateTimeImmutable $checkOutDate,
    ): array {
        $event = new CheckInPerformed(
            $id->toString(),
            $bookingId->toString(),
            $guestId,
            $roomId,
            $checkInDate,
            $checkOutDate,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function checkOut(): array
    {
        if ($this->status !== 'active') {
            throw new \DomainException('Cannot check out a stay that is not active');
        }

        $event = new CheckOutPerformed(
            $this->id->toString(),
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function applyCheckInPerformed(CheckInPerformed $event): self
    {
        return new self(
            StayId::fromString($event->id),
            BookingId::fromString($event->bookingId),
            $event->guestId,
            $event->roomId,
            $event->checkInDate,
            $event->checkOutDate,
            'active',
            $event->timestamp,
        );
    }

    public function applyCheckOutPerformed(CheckOutPerformed $event): self
    {
        return new self(
            $this->id,
            $this->bookingId,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            'completed',
            $this->checkedInAt,
            $event->timestamp,
        );
    }
}