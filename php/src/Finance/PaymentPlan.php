<?php

declare(strict_types=1);

namespace Finance;

use Finance\DomainEvent\PaymentPlanCreated;
use Finance\DomainEvent\InstallmentPaid;
use Finance\DomainEvent\PaymentPlanCompleted;
use Finance\DomainEvent\PaymentPlanCancelled;

final class PaymentPlan
{
    private function __construct(
        private PaymentPlanId $id,
        private string $invoiceId,
        private array $installments,
        private \DateTimeImmutable $startDate,
        private \DateTimeImmutable $endDate,
        private array $paidInstallments = [],
        private string $status = 'active',
        private ?string $createdBy = null,
        private ?\DateTimeImmutable $completedAt = null,
        private ?\DateTimeImmutable $cancelledAt = null,
        private ?string $cancellationReason = null
    ) {
    }
    
    public static function create(
        PaymentPlanId $id,
        string $invoiceId,
        array $installments,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        ?string $createdBy = null
    ): array {
        // Validate installments
        if (empty($installments)) {
            throw new \InvalidArgumentException('Payment plan must have at least one installment');
        }
        
        foreach ($installments as $installment) {
            if (!isset($installment['amount']) || !is_numeric($installment['amount']) || $installment['amount'] <= 0) {
                throw new \InvalidArgumentException('Each installment must have a valid positive amount');
            }
            
            if (!isset($installment['dueDate']) || !($installment['dueDate'] instanceof \DateTimeImmutable)) {
                throw new \InvalidArgumentException('Each installment must have a valid due date');
            }
        }
        
        if ($startDate > $endDate) {
            throw new \InvalidArgumentException('Start date must be before end date');
        }
        
        $event = new PaymentPlanCreated(
            $id->toString(),
            $invoiceId,
            $installments,
            $startDate,
            $endDate,
            $createdBy,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function recordInstallmentPayment(
        int $installmentIndex,
        \DateTimeImmutable $paymentDate,
        ?string $notes = null
    ): array {
        if ($this->status !== 'active') {
            throw new \DomainException("Cannot record payment for a {$this->status} payment plan");
        }
        
        if (!isset($this->installments[$installmentIndex])) {
            throw new \InvalidArgumentException("Installment index {$installmentIndex} does not exist");
        }
        
        if (in_array($installmentIndex, array_column($this->paidInstallments, 'index'))) {
            throw new \DomainException("Installment {$installmentIndex} has already been paid");
        }
        
        $installment = $this->installments[$installmentIndex];
        
        $event = new InstallmentPaid(
            $this->id->toString(),
            $this->invoiceId,
            $installmentIndex,
            $installment['amount'],
            $installment['dueDate'],
            $paymentDate,
            $notes,
            new \DateTimeImmutable()
        );
        
        $events = [$event];
        
        // Check if all installments are now paid
        $paidInstallments = array_merge($this->paidInstallments, [
            [
                'index' => $installmentIndex,
                'paidAt' => $paymentDate
            ]
        ]);
        
        if (count($paidInstallments) === count($this->installments)) {
            $completionEvent = new PaymentPlanCompleted(
                $this->id->toString(),
                $this->invoiceId,
                new \DateTimeImmutable()
            );
            
            $events[] = $completionEvent;
        }
        
        return $events;
    }
    
    public function cancel(string $reason): array
    {
        if ($this->status !== 'active') {
            throw new \DomainException("Cannot cancel a {$this->status} payment plan");
        }
        
        $event = new PaymentPlanCancelled(
            $this->id->toString(),
            $this->invoiceId,
            $reason,
            new \DateTimeImmutable()
        );
        
        return [$event];
    }
    
    public function applyPaymentPlanCreated(PaymentPlanCreated $event): self
    {
        return new self(
            PaymentPlanId::fromString($event->paymentPlanId()),
            $event->invoiceId(),
            $event->installments(),
            $event->startDate(),
            $event->endDate(),
            [],
            'active',
            $event->createdBy()
        );
    }
    
    public function applyInstallmentPaid(InstallmentPaid $event): self
    {
        return new self(
            $this->id,
            $this->invoiceId,
            $this->installments,
            $this->startDate,
            $this->endDate,
            array_merge($this->paidInstallments, [
                [
                    'index' => $event->installmentIndex(),
                    'amount' => $event->amount(),
                    'dueDate' => $event->dueDate(),
                    'paidAt' => $event->paymentDate(),
                    'notes' => $event->notes()
                ]
            ]),
            $this->status,
            $this->createdBy,
            $this->completedAt,
            $this->cancelledAt,
            $this->cancellationReason
        );
    }
    
    public function applyPaymentPlanCompleted(PaymentPlanCompleted $event): self
    {
        return new self(
            $this->id,
            $this->invoiceId,
            $this->installments,
            $this->startDate,
            $this->endDate,
            $this->paidInstallments,
            'completed',
            $this->createdBy,
            $event->occurredAt(),
            $this->cancelledAt,
            $this->cancellationReason
        );
    }
    
    public function applyPaymentPlanCancelled(PaymentPlanCancelled $event): self
    {
        return new self(
            $this->id,
            $this->invoiceId,
            $this->installments,
            $this->startDate,
            $this->endDate,
            $this->paidInstallments,
            'cancelled',
            $this->createdBy,
            $this->completedAt,
            $event->occurredAt(),
            $event->reason()
        );
    }
}