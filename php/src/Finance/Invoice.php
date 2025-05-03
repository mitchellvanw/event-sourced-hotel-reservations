<?php

declare(strict_types=1);

namespace App\Finance;

use App\Finance\DomainEvent\InvoiceCreated;
use App\Finance\DomainEvent\InvoiceFinalized;
use App\Finance\DomainEvent\ChargeAdded;

final class Invoice
{
    private function __construct(
        private InvoiceId $id,
        private string $reservationId,
        private string $guestId,
        private float $totalAmount,
        private array $charges = [],
        private bool $finalized = false,
        private ?\DateTimeImmutable $dueDate = null
    ) {
    }
    
    public static function create(
        InvoiceId $id,
        string $reservationId,
        string $guestId,
        float $initialAmount = 0
    ): array {
        $event = new InvoiceCreated(
            $id->toString(),
            $reservationId,
            $guestId,
            $initialAmount,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function addCharge(string $description, float $amount): array
    {
        if ($this->finalized) {
            throw new \DomainException('Cannot add charges to a finalized invoice');
        }
        
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Charge amount must be positive');
        }
        
        $event = new ChargeAdded(
            $this->id->toString(),
            $description,
            $amount,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function finalize(\DateTimeImmutable $dueDate): array
    {
        if ($this->finalized) {
            throw new \DomainException('Invoice is already finalized');
        }
        
        $event = new InvoiceFinalized(
            $this->id->toString(),
            $this->totalAmount,
            $dueDate,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyInvoiceCreated(InvoiceCreated $event): self
    {
        return new self(
            InvoiceId::fromString($event->invoiceId),
            $event->reservationId,
            $event->guestId,
            $event->amount
        );
    }
    
    public function applyChargeAdded(ChargeAdded $event): self
    {
        $invoice = new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $this->totalAmount + $event->amount,
            array_merge($this->charges, [
                [
                    'description' => $event->description,
                    'amount' => $event->amount,
                    'timestamp' => $event->timestamp
                ]
            ]),
            $this->finalized,
            $this->dueDate
        );
        
        return $invoice;
    }
    
    public function applyInvoiceFinalized(InvoiceFinalized $event): self
    {
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $event->totalAmount,
            $this->charges,
            true,
            $event->dueDate
        );
    }
}