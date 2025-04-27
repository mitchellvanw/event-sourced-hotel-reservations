<?php

declare(strict_types=1);

namespace App\Reservation;

final readonly class Reservation
{
    public function __construct(
        private string $id,
        private string $guestId,
        private string $roomId,
        private \DateTimeImmutable $checkInDate,
        private \DateTimeImmutable $checkOutDate,
        private string $status = 'created',
        private ?string $notes = null,
        private ?\DateTimeImmutable $confirmedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null,
        private ?\DateTimeImmutable $checkedInAt = null,
        private ?\DateTimeImmutable $checkedOutAt = null,
        private ?\DateTimeImmutable $noShowRecordedAt = null,
    ) {
    }

    public static function create(
        string $id,
        string $guestId,
        string $roomId,
        \DateTimeImmutable $checkInDate,
        \DateTimeImmutable $checkOutDate,
        ?string $notes = null,
    ): array {
        $event = new ReservationCreated(
            $id,
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
            throw new \DomainException('Cannot confirm reservation that is not in created status');
        }

        $event = new ReservationConfirmed(
            $this->id,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function modify(\DateTimeImmutable $checkInDate, \DateTimeImmutable $checkOutDate, ?string $notes = null): array
    {
        if ($this->status !== 'created' && $this->status !== 'confirmed') {
            throw new \DomainException('Cannot modify reservation that is not in created or confirmed status');
        }

        $event = new ReservationModified(
            $this->id,
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
            throw new \DomainException('Reservation is already cancelled');
        }

        if ($this->status === 'checked_in' || $this->status === 'checked_out') {
            throw new \DomainException('Cannot cancel reservation after check-in');
        }

        $event = new ReservationCancelled(
            $this->id,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function checkIn(): array
    {
        if ($this->status !== 'confirmed') {
            throw new \DomainException('Cannot check in a reservation that is not confirmed');
        }

        $event = new CheckInPerformed(
            $this->id,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function checkOut(): array
    {
        if ($this->status !== 'checked_in') {
            throw new \DomainException('Cannot check out a reservation that is not checked in');
        }

        $event = new CheckOutPerformed(
            $this->id,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function recordNoShow(): array
    {
        if ($this->status !== 'confirmed') {
            throw new \DomainException('Can only record no-show for confirmed reservations');
        }

        $event = new NoShowRecorded(
            $this->id,
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function applyReservationCreated(ReservationCreated $event): self
    {
        return new self(
            $event->id,
            $event->guestId,
            $event->roomId,
            $event->checkInDate,
            $event->checkOutDate,
            'created',
            $event->notes,
        );
    }

    public function applyReservationConfirmed(ReservationConfirmed $event): self
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

    public function applyReservationModified(ReservationModified $event): self
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
            $this->checkedInAt,
            $this->checkedOutAt,
            $this->noShowRecordedAt,
        );
    }

    public function applyReservationCancelled(ReservationCancelled $event): self
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
            $this->checkedInAt,
            $this->checkedOutAt,
            $this->noShowRecordedAt,
        );
    }

    public function applyCheckInPerformed(CheckInPerformed $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            'checked_in',
            $this->notes,
            $this->confirmedAt,
            $this->cancelledAt,
            $event->timestamp,
            $this->checkedOutAt,
            $this->noShowRecordedAt,
        );
    }

    public function applyCheckOutPerformed(CheckOutPerformed $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            'checked_out',
            $this->notes,
            $this->confirmedAt,
            $this->cancelledAt,
            $this->checkedInAt,
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
            $this->checkedInAt,
            $this->checkedOutAt,
            $event->timestamp,
        );
    }
}