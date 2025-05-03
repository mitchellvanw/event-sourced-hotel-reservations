<?php

declare(strict_types=1);

namespace App\Reservation;

use App\Reservation\DomainEvent\BookingCreated;
use App\Reservation\DomainEvent\BookingConfirmed;
use App\Reservation\DomainEvent\BookingModified;
use App\Reservation\DomainEvent\BookingCancelled;
use App\Reservation\DomainEvent\NoShowRecorded;
use App\Reservation\DomainEvent\BookingFulfilled;
use App\Reservation\DomainEvent\SpecialRequestAdded;
use App\Reservation\DomainEvent\BookingAmended;

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
        private array $specialRequests = [],
        private ?StayId $stayId = null,
        private ?\DateTimeImmutable $confirmedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null,
        private ?\DateTimeImmutable $noShowRecordedAt = null,
        private ?\DateTimeImmutable $fulfilledAt = null,
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

        if ($this->status === 'checked_in' || $this->status === 'checked_out' || $this->status === 'no_show' || $this->status === 'fulfilled') {
            throw new \DomainException('Cannot cancel booking after check-in, check-out, no-show, or fulfillment');
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

    public function fulfill(StayId $stayId): array
    {
        if ($this->status !== 'confirmed') {
            throw new \DomainException('Can only fulfill confirmed bookings');
        }

        if ($this->status === 'fulfilled') {
            throw new \DomainException('Booking is already fulfilled');
        }

        $event = new BookingFulfilled(
            $this->id->toString(),
            $stayId->toString(),
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function addSpecialRequest(string $requestType, string $requestDetails): array
    {
        if ($this->status === 'cancelled' || $this->status === 'no_show') {
            throw new \DomainException('Cannot add special requests to cancelled or no-show bookings');
        }

        $event = new SpecialRequestAdded(
            $this->id->toString(),
            $requestType,
            $requestDetails,
            'pending',
            new \DateTimeImmutable(),
        );

        return [$event];
    }

    public function amend(string $amendmentType, string $amendmentDetails, string $amendedBy): array
    {
        if ($this->status === 'cancelled' || $this->status === 'no_show') {
            throw new \DomainException('Cannot amend cancelled or no-show bookings');
        }

        $event = new BookingAmended(
            $this->id->toString(),
            $amendmentType,
            $amendmentDetails,
            $amendedBy,
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
            $this->specialRequests,
            $this->stayId,
            $event->timestamp,
            $this->cancelledAt,
            $this->noShowRecordedAt,
            $this->fulfilledAt,
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
            $this->specialRequests,
            $this->stayId,
            $this->confirmedAt,
            $this->cancelledAt,
            $this->noShowRecordedAt,
            $this->fulfilledAt,
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
            $this->specialRequests,
            $this->stayId,
            $this->confirmedAt,
            $event->timestamp,
            $this->noShowRecordedAt,
            $this->fulfilledAt,
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
            $this->specialRequests,
            $this->stayId,
            $this->confirmedAt,
            $this->cancelledAt,
            $event->timestamp,
            $this->fulfilledAt,
        );
    }

    public function applyBookingFulfilled(BookingFulfilled $event): self
    {
        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            'fulfilled',
            $this->notes,
            $this->specialRequests,
            StayId::fromString($event->stayId),
            $this->confirmedAt,
            $this->cancelledAt,
            $this->noShowRecordedAt,
            $event->timestamp,
        );
    }

    public function applySpecialRequestAdded(SpecialRequestAdded $event): self
    {
        $specialRequests = $this->specialRequests;
        $specialRequests[] = [
            'type' => $event->requestType,
            'details' => $event->requestDetails,
            'status' => $event->status,
            'timestamp' => $event->timestamp,
        ];

        return new self(
            $this->id,
            $this->guestId,
            $this->roomId,
            $this->checkInDate,
            $this->checkOutDate,
            $this->status,
            $this->notes,
            $specialRequests,
            $this->stayId,
            $this->confirmedAt,
            $this->cancelledAt,
            $this->noShowRecordedAt,
            $this->fulfilledAt,
        );
    }

    public function applyBookingAmended(BookingAmended $event): self
    {
        // For administrative amendments, we don't change core booking data,
        // but we might want to track them in an amendments log in a real system
        return $this;
    }
}