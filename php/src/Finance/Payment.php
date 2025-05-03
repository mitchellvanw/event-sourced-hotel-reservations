<?php

declare(strict_types=1);

namespace App\Finance;

use App\Finance\DomainEvent\PaymentRecorded;
use App\Finance\DomainEvent\RefundIssued;

final class Payment
{
    private function __construct(
        private PaymentId $id,
        private InvoiceId $invoiceId,
        private string $guestId,
        private float $amount,
        private string $method,
        private bool $refunded = false,
        private ?string $refundReason = null
    ) {
    }
    
    public static function record(
        PaymentId $id,
        InvoiceId $invoiceId,
        string $guestId,
        float $amount,
        string $method
    ): array {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be positive');
        }
        
        $event = new PaymentRecorded(
            $id->toString(),
            $invoiceId->toString(),
            $guestId,
            $amount,
            $method,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function issueRefund(string $reason): array
    {
        if ($this->refunded) {
            throw new \DomainException('Payment has already been refunded');
        }
        
        $event = new RefundIssued(
            $this->id->toString(),
            $this->invoiceId->toString(),
            $this->guestId,
            $this->amount,
            $reason,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyPaymentRecorded(PaymentRecorded $event): self
    {
        return new self(
            PaymentId::fromString($event->paymentId),
            InvoiceId::fromString($event->invoiceId),
            $event->guestId,
            $event->amount,
            $event->method
        );
    }
    
    public function applyRefundIssued(RefundIssued $event): self
    {
        return new self(
            $this->id,
            $this->invoiceId,
            $this->guestId,
            $this->amount,
            $this->method,
            true,
            $event->reason
        );
    }
}