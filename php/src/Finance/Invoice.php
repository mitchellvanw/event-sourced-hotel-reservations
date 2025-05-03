<?php

declare(strict_types=1);

namespace Finance;

use Finance\DomainEvent\InvoiceCreated;
use Finance\DomainEvent\InvoiceFinalized;
use Finance\DomainEvent\ChargeAdded;
use Finance\DomainEvent\InvoiceCancelled;
use Finance\DomainEvent\InvoiceAdjusted;
use Finance\DomainEvent\PaymentStatusChanged;
use Finance\DomainEvent\InvoiceReconciled;
use Finance\DomainEvent\PaymentPlanCreated;
use Finance\DomainEvent\PartialPaymentRecorded;

final class Invoice
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_FINALIZED = 'finalized';
    public const STATUS_PARTIALLY_PAID = 'partially_paid';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_CANCELLED = 'cancelled';

    private function __construct(
        private InvoiceId $id,
        private string $reservationId,
        private string $guestId,
        private float $totalAmount,
        private array $charges = [],
        private string $status = self::STATUS_DRAFT,
        private ?\DateTimeImmutable $dueDate = null,
        private float $paidAmount = 0,
        private ?string $paymentPlanId = null,
        private array $paymentHistory = [],
        private array $adjustmentHistory = []
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
        if ($this->status !== self::STATUS_DRAFT) {
            throw new \DomainException('Cannot add charges to a non-draft invoice');
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
        if ($this->status !== self::STATUS_DRAFT) {
            throw new \DomainException('Only draft invoices can be finalized');
        }
        
        $event = new InvoiceFinalized(
            $this->id->toString(),
            $this->totalAmount,
            $dueDate,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }

    public function cancel(string $reason, ?string $cancelledBy = null): array
    {
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Invoice is already cancelled');
        }

        if ($this->status === self::STATUS_PAID) {
            throw new \DomainException('Cannot cancel a fully paid invoice');
        }
        
        $events = [];
        
        $cancelEvent = new InvoiceCancelled(
            $this->id->toString(),
            $reason,
            $cancelledBy,
            new \DateTimeImmutable()
        );
        
        $events[] = $cancelEvent;
        
        $statusEvent = new PaymentStatusChanged(
            $this->id->toString(),
            $this->status,
            self::STATUS_CANCELLED,
            "Invoice cancelled: $reason",
            new \DateTimeImmutable()
        );
        
        $events[] = $statusEvent;
        
        return $events;
    }

    public function adjust(array $adjustments, string $reason, ?string $adjustedBy = null): array
    {
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot adjust a cancelled invoice');
        }
        
        if ($this->status === self::STATUS_DRAFT) {
            throw new \DomainException('Finalize the invoice before making adjustments');
        }
        
        $oldTotal = $this->totalAmount;
        $newTotal = $oldTotal;
        
        foreach ($adjustments as $adjustment) {
            if (!isset($adjustment['amount']) || !is_numeric($adjustment['amount'])) {
                throw new \InvalidArgumentException('Each adjustment must have a valid amount');
            }
            
            if (!isset($adjustment['description']) || empty($adjustment['description'])) {
                throw new \InvalidArgumentException('Each adjustment must have a description');
            }
            
            $newTotal += $adjustment['amount'];
        }
        
        if ($newTotal < 0) {
            throw new \InvalidArgumentException('Adjustments cannot result in a negative invoice total');
        }
        
        if ($newTotal < $this->paidAmount) {
            throw new \InvalidArgumentException('Adjusted total cannot be less than the amount already paid');
        }
        
        $event = new InvoiceAdjusted(
            $this->id->toString(),
            $adjustments,
            $reason,
            $oldTotal,
            $newTotal,
            $adjustedBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }

    public function recordPartialPayment(
        PaymentId $paymentId,
        float $amount,
        string $method,
        ?string $notes = null
    ): array {
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot record payment for a cancelled invoice');
        }
        
        if ($this->status === self::STATUS_DRAFT) {
            throw new \DomainException('Cannot record payment for a draft invoice');
        }
        
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Payment amount must be positive');
        }
        
        $newPaidAmount = $this->paidAmount + $amount;
        
        if ($newPaidAmount > $this->totalAmount) {
            throw new \InvalidArgumentException('Payment amount cannot exceed the remaining balance');
        }
        
        $remainingBalance = $this->totalAmount - $newPaidAmount;
        
        $events = [];
        
        $paymentEvent = new PartialPaymentRecorded(
            $this->id->toString(),
            $paymentId->toString(),
            $amount,
            $method,
            $remainingBalance,
            $notes,
            new \DateTimeImmutable()
        );
        
        $events[] = $paymentEvent;
        
        $oldStatus = $this->status;
        $newStatus = $remainingBalance === 0 ? self::STATUS_PAID : self::STATUS_PARTIALLY_PAID;
        
        if ($oldStatus !== $newStatus) {
            $statusEvent = new PaymentStatusChanged(
                $this->id->toString(),
                $oldStatus,
                $newStatus,
                "Payment of $amount recorded",
                new \DateTimeImmutable()
            );
            
            $events[] = $statusEvent;
        }
        
        return $events;
    }

    public function createPaymentPlan(
        PaymentPlanId $paymentPlanId,
        array $installments,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $createdBy = null
    ): array {
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot create payment plan for a cancelled invoice');
        }
        
        if ($this->status === self::STATUS_DRAFT) {
            throw new \DomainException('Cannot create payment plan for a draft invoice');
        }
        
        if ($this->status === self::STATUS_PAID) {
            throw new \DomainException('Cannot create payment plan for a fully paid invoice');
        }
        
        if ($this->paymentPlanId !== null) {
            throw new \DomainException('A payment plan already exists for this invoice');
        }
        
        if (empty($installments)) {
            throw new \InvalidArgumentException('Payment plan must have at least one installment');
        }
        
        $totalInstallmentAmount = 0;
        
        foreach ($installments as $installment) {
            if (!isset($installment['amount']) || !is_numeric($installment['amount']) || $installment['amount'] <= 0) {
                throw new \InvalidArgumentException('Each installment must have a valid positive amount');
            }
            
            if (!isset($installment['dueDate']) || !($installment['dueDate'] instanceof \DateTimeImmutable)) {
                throw new \InvalidArgumentException('Each installment must have a valid due date');
            }
            
            $totalInstallmentAmount += $installment['amount'];
        }
        
        $remainingBalance = $this->totalAmount - $this->paidAmount;
        
        if (abs($totalInstallmentAmount - $remainingBalance) > 0.01) {
            throw new \InvalidArgumentException('Total installment amounts must equal the remaining balance');
        }
        
        $event = new PaymentPlanCreated(
            $this->id->toString(),
            $paymentPlanId->toString(),
            $installments,
            $startDate,
            $endDate,
            $createdBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }

    public function reconcile(array $reconciliationDetails, ?string $reconciledBy = null): array
    {
        if ($this->status === self::STATUS_CANCELLED) {
            throw new \DomainException('Cannot reconcile a cancelled invoice');
        }
        
        if ($this->status === self::STATUS_DRAFT) {
            throw new \DomainException('Cannot reconcile a draft invoice');
        }
        
        $event = new InvoiceReconciled(
            $this->id->toString(),
            $reconciliationDetails,
            $reconciledBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }

    public function markAsOverdue(): array
    {
        if ($this->status !== self::STATUS_FINALIZED && $this->status !== self::STATUS_PARTIALLY_PAID) {
            throw new \DomainException('Only finalized or partially paid invoices can be marked as overdue');
        }
        
        if ($this->dueDate === null || $this->dueDate > new \DateTimeImmutable()) {
            throw new \DomainException('Invoice is not yet due');
        }
        
        $event = new PaymentStatusChanged(
            $this->id->toString(),
            $this->status,
            self::STATUS_OVERDUE,
            "Invoice past due date: {$this->dueDate->format('Y-m-d')}",
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyInvoiceCreated(InvoiceCreated $event): self
    {
        return new self(
            InvoiceId::fromString($event->invoiceId()),
            $event->reservationId(),
            $event->guestId(),
            $event->amount()
        );
    }
    
    public function applyChargeAdded(ChargeAdded $event): self
    {
        $invoice = new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $this->totalAmount + $event->amount(),
            array_merge($this->charges, [
                [
                    'description' => $event->description(),
                    'amount' => $event->amount(),
                    'timestamp' => $event->occurredAt()
                ]
            ]),
            $this->status,
            $this->dueDate,
            $this->paidAmount,
            $this->paymentPlanId,
            $this->paymentHistory,
            $this->adjustmentHistory
        );
        
        return $invoice;
    }
    
    public function applyInvoiceFinalized(InvoiceFinalized $event): self
    {
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $event->totalAmount(),
            $this->charges,
            self::STATUS_FINALIZED,
            $event->dueDate(),
            $this->paidAmount,
            $this->paymentPlanId,
            $this->paymentHistory,
            $this->adjustmentHistory
        );
    }

    public function applyInvoiceCancelled(InvoiceCancelled $event): self
    {
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $this->totalAmount,
            $this->charges,
            self::STATUS_CANCELLED,
            $this->dueDate,
            $this->paidAmount,
            $this->paymentPlanId,
            $this->paymentHistory,
            array_merge($this->adjustmentHistory, [
                [
                    'type' => 'cancellation',
                    'reason' => $event->reason(),
                    'by' => $event->cancelledBy(),
                    'timestamp' => $event->occurredAt()
                ]
            ])
        );
    }

    public function applyInvoiceAdjusted(InvoiceAdjusted $event): self
    {
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $event->newTotal(),
            $this->charges,
            $this->status,
            $this->dueDate,
            $this->paidAmount,
            $this->paymentPlanId,
            $this->paymentHistory,
            array_merge($this->adjustmentHistory, [
                [
                    'type' => 'adjustment',
                    'adjustments' => $event->adjustments(),
                    'reason' => $event->reason(),
                    'oldTotal' => $event->oldTotal(),
                    'newTotal' => $event->newTotal(),
                    'by' => $event->adjustedBy(),
                    'timestamp' => $event->occurredAt()
                ]
            ])
        );
    }

    public function applyPaymentStatusChanged(PaymentStatusChanged $event): self
    {
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $this->totalAmount,
            $this->charges,
            $event->newStatus(),
            $this->dueDate,
            $this->paidAmount,
            $this->paymentPlanId,
            $this->paymentHistory,
            $this->adjustmentHistory
        );
    }

    public function applyPartialPaymentRecorded(PartialPaymentRecorded $event): self
    {
        $newPaidAmount = $this->paidAmount + $event->amount();
        
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $this->totalAmount,
            $this->charges,
            $this->status,
            $this->dueDate,
            $newPaidAmount,
            $this->paymentPlanId,
            array_merge($this->paymentHistory, [
                [
                    'paymentId' => $event->paymentId(),
                    'amount' => $event->amount(),
                    'method' => $event->method(),
                    'notes' => $event->notes(),
                    'timestamp' => $event->occurredAt()
                ]
            ]),
            $this->adjustmentHistory
        );
    }

    public function applyPaymentPlanCreated(PaymentPlanCreated $event): self
    {
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $this->totalAmount,
            $this->charges,
            $this->status,
            $this->dueDate,
            $this->paidAmount,
            $event->paymentPlanId(),
            $this->paymentHistory,
            $this->adjustmentHistory
        );
    }

    public function applyInvoiceReconciled(InvoiceReconciled $event): self
    {
        return new self(
            $this->id,
            $this->reservationId,
            $this->guestId,
            $this->totalAmount,
            $this->charges,
            $this->status,
            $this->dueDate,
            $this->paidAmount,
            $this->paymentPlanId,
            $this->paymentHistory,
            array_merge($this->adjustmentHistory, [
                [
                    'type' => 'reconciliation',
                    'details' => $event->reconciliationDetails(),
                    'by' => $event->reconciledBy(),
                    'timestamp' => $event->occurredAt()
                ]
            ])
        );
    }
}