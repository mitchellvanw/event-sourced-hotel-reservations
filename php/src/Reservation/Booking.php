<?php

declare(strict_types=1);

namespace App\Reservation;

use App\Reservation\DomainEvent\BookingCreated;
use App\Reservation\DomainEvent\BookingConfirmed;
use App\Reservation\DomainEvent\BookingModified;
use App\Reservation\DomainEvent\BookingCancelled;
use App\Reservation\DomainEvent\NoShowRecorded;

final class Booking
{
    private function __construct(
        private BookingId $id,
        private string $guestId,
        private string $roomId,
        private \DateTimeImmutable $checkInDate,
        private \DateTimeImmutable $checkOutDate,
        private string $status = 'created',
        private ?string $notes = null,
        private ?\DateTimeImmutable $confirmedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null,
        private ?\DateTimeImmutable $noShowRecordedAt = null,
    ) {
    }

    public static function create(
        BookingId $id,
        string $guestId,
        string $roomId,
        \DateTimeImmutable $checkInDate,
        \DateTimeImmutable $checkOutDate,
        ?string $notes = null,
    ): array {
        $event = new BookingCreated(
            $id->toString(),
            $guestId,
            $roomId,
            $checkInDate,
            $checkOutDate,
            $notes,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function confirm(): array
    {
        if ($this->status !== 'created') {
            throw new \DomainException('Cannot confirm booking that is not in created status');
        }

        $event = new BookingConfirmed(
            $this->id->toString(),
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function modify(\DateTimeImmutable $checkInDate, \DateTimeImmutable $checkOutDate, ?string $notes = null): array
    {
        if ($this->status !== 'created' && $this->status !== 'confirmed') {
            throw new \DomainException('Cannot modify booking that is not in created or confirmed status');
        }

        $event = new BookingModified(
            $this->id->toString(),
            $checkInDate,
            $checkOutDate,
            $notes,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function cancel(): array
    {
        if ($this->status === 'cancelled') {
            throw new \DomainException('Booking is already cancelled');
        }

        if ($this->status === 'checked_in' || $this->status === 'checked_out' || $this->status === 'no_show') {
            throw new \DomainException('Cannot cancel booking after check-in, check-out, or no-show');
        }

        $event = new BookingCancelled(
            $this->id->toString(),
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function recordNoShow(): array
    {
        if ($this->status !== 'confirmed') {
            throw new \DomainException('Can only record no-show for confirmed bookings');
        }

        $event = new NoShowRecorded(
            $this->id->toString(),
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function applyBookingCreated(BookingCreated $event): self
    {
        return new self(
            BookingId::fromString($event->id),
            $event->guestId,
            $event->roomId,
            $event->checkInDate,
            $event->checkOutDate,
            'created',
            $event->notes,
        );
    }

    public function applyBookingConfirmed(BookingConfirmed $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            'confirmed',
            $this->notes,
            $event->timestamp,
        );
    }

    public function applyBookingModified(BookingModified $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $event->checkInDate,
            $event->checkOutDate,
            $this->status,
            $event->notes,
            $this->confirmedAt,
            $this->cancelledAt,
            $this->noShowRecordedAt,
        );
    }

    public function applyBookingCancelled(BookingCancelled $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            'cancelled',
            $this->notes,
            $this->confirmedAt,
            $event->timestamp,
            $this->noShowRecordedAt,
        );
    }

    public function applyNoShowRecorded(NoShowRecorded $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            'no_show',
            $this->notes,
            $this->confirmedAt,
            $this->cancelledAt,
            $event->timestamp,
        );
    }
}