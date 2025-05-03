<?php

declare(strict_types=1);

namespace App\Reservation;

use App\Reservation\DomainEvent\CheckInPerformed;
use App\Reservation\DomainEvent\CheckOutPerformed;
use App\Reservation\DomainEvent\StayExtended;
use App\Reservation\DomainEvent\EarlyCheckOutRequested;
use App\Reservation\DomainEvent\BookingFulfilled;

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
        $checkInEvent = new CheckInPerformed(
            $id->toString(),
            $bookingId->toString(),
            $guestId,
            $roomId,
            $checkInDate,
            $checkOutDate,
            new \DateTimeImmutable(),
        );

        $bookingFulfilledEvent = new BookingFulfilled(
            $bookingId->toString(),
            $id->toString(),
            new \DateTimeImmutable(),
        );

        return [$checkInEvent, $bookingFulfilledEvent];
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

    public function extendStay(\DateTimeImmutable $newCheckOutDate, ?string $reason = null): array
    {
        if ($this->status !== 'active') {
            throw new \DomainException('Cannot extend a stay that is not active');
        }

        if ($newCheckOutDate <= $this->checkOutDate) {
            throw new \InvalidArgumentException('New check-out date must be after current check-out date');
        }

        $event = new StayExtended(
            $this->id->toString(),
            $this->bookingId->toString(),
            $this->checkOutDate,
            $newCheckOutDate,
            $reason,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function requestEarlyCheckOut(\DateTimeImmutable $newCheckOutDate, ?string $reason = null): array
    {
        if ($this->status !== 'active') {
            throw new \DomainException('Cannot request early check-out for a stay that is not active');
        }

        if ($newCheckOutDate >= $this->checkOutDate) {
            throw new \InvalidArgumentException('New check-out date must be before current check-out date');
        }

        $event = new EarlyCheckOutRequested(
            $this->id->toString(),
            $this->bookingId->toString(),
            $this->checkOutDate,
            $newCheckOutDate,
            $reason,
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

    public function applyStayExtended(StayExtended $event): self
    {
        return new self(
            $this->id,
            $this->bookingId,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $event->newCheckOutDate,
            $this->status,
            $this->checkedInAt,
            $this->checkedOutAt,
        );
    }

    public function applyEarlyCheckOutRequested(EarlyCheckOutRequested $event): self
    {
        return new self(
            $this->id,
            $this->bookingId,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $event->newCheckOutDate,
            $this->status,
            $this->checkedInAt,
            $this->checkedOutAt,
        );
    }
}