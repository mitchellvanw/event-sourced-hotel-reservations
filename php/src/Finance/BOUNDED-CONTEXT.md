# Bounded Context Canvas - Finance

## Name
Finance

## Description
Manages all financial aspects of the hotel's operations, including invoicing, payments, refunds, and financial reporting. This context ensures accurate tracking of financial transactions and compliance with accounting standards.

## Strategic Classification

### Domain
- **Core Domain**: Financial management is central to the hotel's business operations and profitability.

### Business Model
- **Revenue Generator**: Directly responsible for tracking and optimizing the hotel's revenue streams.

### Evolution
- **Mature**: Financial systems follow well-established accounting principles, though implementation details continue to evolve.

## Domain Roles

### Key Domain Roles
- **Invoice**: Represents a bill issued to a guest for services rendered
- **Payment**: Represents money received from a guest
- **PaymentPlan**: Represents an arrangement for installment payments
- **Refund**: Represents money returned to a guest
- **Account**: Represents a financial relationship with a guest or vendor

## Ubiquitous Language

### Key Terms
- **Charge**: An individual billable item added to an invoice
- **Invoice**: A document listing charges and requesting payment
- **Payment**: A financial transaction that reduces an invoice balance
- **Refund**: A return of funds previously received
- **Adjustment**: A modification to an invoice after it has been finalized
- **Reconciliation**: The process of verifying financial records match actual transactions
- **Payment Status**: The current state of an invoice (draft, finalized, partially paid, paid, overdue, cancelled)
- **Installment**: A portion of a total payment due at specified intervals

## Business Decisions

### Key Business Rules
- Invoices are initially created in draft status and must be finalized before payment
- Charges can only be added to invoices in draft status
- Payments must be validated against the invoice total
- Refunds require approval if they exceed certain thresholds
- Overdue invoices trigger automated reminder processes
- Payment plans must cover the full remaining balance over time
- Cancelled invoices cannot be paid or modified further
- Adjustments to finalized invoices must be tracked with reasons

## Domain Events

### Key Events
- **InvoiceCreated**: A new invoice has been generated
- **ChargeAdded**: A new charge has been added to an invoice
- **InvoiceFinalized**: An invoice has been completed and is ready for payment
- **InvoiceCancelled**: An invoice has been cancelled
- **InvoiceAdjusted**: Changes have been made to a finalized invoice
- **PaymentRecorded**: Money has been received from a guest
- **PartialPaymentRecorded**: Partial payment has been made toward an invoice
- **PaymentStatusChanged**: The payment status of an invoice has changed
- **RefundIssued**: Money has been returned to a guest
- **PaymentPlanCreated**: An installment plan has been established
- **InstallmentPaid**: A payment plan installment has been received
- **PaymentPlanCompleted**: All installments in a payment plan have been paid
- **InvoiceReconciled**: An invoice has been verified against transactions
- **InvoiceOverdue**: An invoice has not been paid by the due date

## Commands

### Key Commands
- **CreateInvoice**: Generate a new invoice
- **AddCharge**: Add a new billable item to an invoice
- **FinalizeInvoice**: Complete an invoice and make it ready for payment
- **CancelInvoice**: Mark an invoice as cancelled
- **AdjustInvoice**: Make changes to a finalized invoice
- **RecordPayment**: Register received funds against an invoice
- **RecordPartialPayment**: Register a partial payment against an invoice
- **IssueRefund**: Return money to a guest
- **CreatePaymentPlan**: Set up an installment payment schedule
- **RecordInstallmentPayment**: Register an installment payment
- **ReconcileInvoice**: Verify invoice against actual transactions
- **MarkInvoiceAsOverdue**: Flag an invoice as past due

## Queries

### Key Queries
- **GetInvoiceById**: Retrieve details of a specific invoice
- **GetInvoicesByGuest**: Find all invoices for a specific guest
- **GetInvoicesByStatus**: List invoices with a particular payment status
- **GetInvoicePaymentHistory**: View payment history for an invoice
- **GetInvoiceAdjustmentHistory**: View adjustment history for an invoice
- **GetOverdueInvoices**: List all invoices past their due date
- **GetPaymentPlanDetails**: Retrieve details of a payment plan
- **GetDailyRevenue**: Calculate total revenue for a specific day
- **GetPaymentsReport**: Generate a report of all payments within a date range

## Dependencies

### Upstream
- **Reservation**: Provides booking information to generate invoices
- **Guest**: Provides guest information for billing and payments

### Downstream
- **Reporting**: Consumes financial data for business intelligence
- **Accounting**: Uses transaction data for general ledger entries

## Technical Characteristics

### Defining Characteristics
- **Event-sourced**: Maintains complete audit trail of all financial transactions
- **Transactional integrity**: Ensures financial data consistency and accuracy
- **Regulatory compliant**: Designed to meet financial reporting requirements

## Team

### Roles
- Financial analysts and accountants
- Compliance specialists
- Software engineers with finance domain expertise
- UX designers focused on financial interfaces