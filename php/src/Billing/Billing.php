<?php

declare(strict_types=1);

namespace App\Billing;

final readonly class Billing
{
    private string $billingId;
    private string $reservationId;
    private string $guestId;
    private float $totalAmount;
    private float $paidAmount;
    private bool $invoiceGenerated;
    
    private function __construct(string $billingId)
    {
        $this->billingId = $billingId;
    }
    
    public static function create(
        string $billingId,
        string $reservationId,
        string $guestId,
        float $totalAmount
    ): array {
        $billing = new self($billingId);
        $event = new InvoiceGenerated(
            billingId: $billingId,
            reservationId: $reservationId,
            guestId: $guestId,
            amount: $totalAmount,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$billing->applyInvoiceGenerated($event), $event];
    }
    
    public function receivePayment(float $amount): array
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be positive');
        }
        
        if ($this->paidAmount + $amount > $this->totalAmount) {
            throw new \InvalidArgumentException('Payment exceeds total amount');
        }
        
        $event = new PaymentReceived(
            billingId: $this->billingId,
            reservationId: $this->reservationId,
            guestId: $this->guestId,
            amount: $amount,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyPaymentReceived($event), $event];
    }
    
    public function refundPayment(float $amount, string $reason): array
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Refund amount must be positive');
        }
        
        if ($amount > $this->paidAmount) {
            throw new \InvalidArgumentException('Refund cannot exceed paid amount');
        }
        
        $event = new PaymentRefunded(
            billingId: $this->billingId,
            reservationId: $this->reservationId,
            guestId: $this->guestId,
            amount: $amount,
            reason: $reason,
            timestamp: new \DateTimeImmutable(),
        );
        
        return [$this->applyPaymentRefunded($event), $event];
    }
    
    public function applyInvoiceGenerated(InvoiceGenerated $event): self
    {
        $billing = clone $this;
        $billing->reservationId = $event->reservationId;
        $billing->guestId = $event->guestId;
        $billing->totalAmount = $event->amount;
        $billing->paidAmount = 0;
        $billing->invoiceGenerated = true;
        
        return $billing;
    }
    
    public function applyPaymentReceived(PaymentReceived $event): self
    {
        $billing = clone $this;
        $billing->paidAmount += $event->amount;
        
        return $billing;
    }
    
    public function applyPaymentRefunded(PaymentRefunded $event): self
    {
        $billing = clone $this;
        $billing->paidAmount -= $event->amount;
        
        return $billing;
    }
}