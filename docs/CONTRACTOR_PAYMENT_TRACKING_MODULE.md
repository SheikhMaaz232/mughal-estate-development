# Contractor Payment Tracking Module

## Overview

The Contractor Payment Tracking Module provides comprehensive payment management for contractor bills in the Laravel 12 ERP system. It integrates with existing BPV (Bank Payment Voucher) and CPV (Cash Payment Voucher) modules to create a unified payment tracking system.

## Features

### 1. **Payment Recording**
- Link payments to contractor bills
- Support for multiple payment methods (BPV/CPV)
- Track payment amounts and dates
- Add payment remarks and notes

### 2. **Payment Status Management**
- **Verified**: Bill verified, no payments made
- **Partial Paid**: Bill has partial payments
- **Paid**: Bill fully paid
- **Draft**: Unpaid, unverified bill
- **Cancelled**: Bill cancelled

### 3. **Outstanding Balance Tracking**
- Calculate outstanding amount for each bill
- Track total outstanding per contractor
- Monitor payment progress with visual indicators

### 4. **Payment History**
- Complete payment history per bill
- Filter by status, date range
- View payment details and voucher references

### 5. **Payment Reports**
- Contractor-wise payment summary
- Payment statistics and trends
- Export to CSV for analysis
- Filter by date, contractor, status

### 6. **Contractor Payment Dashboard**
- View all payments for a contractor
- Payment summary and statistics
- Outstanding balance overview
- Payment percentage progress

## Database Schema

### contractor_bill_payments Table

```sql
- id (primary key)
- contractor_bill_id (foreign key)
- voucher_id (reference to BPV/CPV)
- voucher_type (BPV, CPV)
- amount (decimal:18,2)
- payment_date (datetime)
- payment_method (string)
- remarks (text)
- status (pending, posted, cancelled)
- created_by (user id)
- updated_by (user id)
- timestamps
- soft deletes
```

## API Endpoints

### Bill Payment Details
```
GET /contractor-payments/bill/{billId}
```
Shows full payment details and history for a bill.

### Make Payment Form
```
GET /contractor-payments/bill/{billId}/make-payment
```
Display form to initiate payment for a bill.

### Initiate Payment
```
POST /contractor-payments/bill/{billId}/initiate
```
Redirect to BPV/CPV creation form with pre-filled data.

### Record Payment
```
POST /contractor-payments/bill/{billId}/record
```
Save payment record after voucher creation.

### Payment History
```
GET /contractor-payments/bill/{billId}/history
```
View detailed payment history for a bill.

### Contractor Payments
```
GET /contractor-payments/contractor/{contractorId}
```
View all payments for a contractor with summary.

### Payment Reports
```
GET /contractor-payments/
```
View all payment records with filters and export options.

### Export Payments
```
GET /contractor-payments/export
```
Export payment data to CSV file.

### Outstanding Balance (AJAX)
```
GET /contractor-payments/api/outstanding/{contractorId}
```
Get outstanding balance for a contractor (JSON response).

### Bill Outstanding (AJAX)
```
GET /contractor-payments/api/bill-outstanding/{billId}
```
Get outstanding amount for a specific bill (JSON response).

## Business Flow

### Step 1: Verify Bill
1. Create contractor bill
2. Add items
3. Click "Verify" to move to "Verified" status

### Step 2: Make Payment
1. Open verified bill
2. Click "Make Payment" button
3. Select payment amount and method (BPV/CPV)
4. System stores payment intent in session

### Step 3: Create Voucher
1. Redirected to BPV/CPV creation form
2. Form auto-filled with:
   - Contractor account
   - Payment amount
   - Tender reference
3. Create and save voucher normally

### Step 4: Link Voucher
1. Payment recorded after voucher creation
2. Links payment to bill
3. Updates bill payment status automatically

## Models & Relationships

### ContractorBillPayment Model

```php
public function bill() // belongsTo ContractorBill
public function createdBy() // belongsTo User
public function updatedBy() // belongsTo User
public function getVoucherDetails() // Get BPV/CPV details
```

### ContractorBill Model Extensions

```php
public function payments() // hasMany ContractorBillPayment
public function getPaidAmount() // Sum of all payments
public function getOutstandingAmount() // Amount - Paid
public function getPostedPayments() // Sum of posted payments
public function getPendingPayments() // Sum of pending payments
public function canAcceptPayment() // Check if bill can accept payments
public function updatePaymentStatus() // Update bill status based on payments
public function getPaymentHistory() // Get all payment records
```

## Service Layer: ContractorPaymentService

### Key Methods

```php
getBillPaymentDetails($billId) // Get full payment information
prepareMakePaymentData($billId) // Prepare data for payment form
recordPayment($billId, $voucherId, $voucherType, $amount, $remarks) // Record payment
getContractorOutstandingBalance($contractorId) // Get contractor's outstanding
getContractorPaymentSummary($contractorId) // Get payment statistics
getContractorPayments($contractorId, $filters) // Get all contractor payments
getBillPaymentHistory($billId, $filters) // Get bill payment history
cancelPayment($paymentId) // Cancel a pending payment
getPaymentReport($filters) // Get payment reports
exportPaymentData($filters) // Export payment data
validatePaymentAmount($billId, $amount) // Validate payment amount
```

## Views

1. **payment-details.blade.php** - Bill payment overview and history
2. **make-payment.blade.php** - Payment initiation form
3. **payment-history.blade.php** - Detailed payment history table
4. **contractor-payments.blade.php** - Contractor payment dashboard
5. **payment-reports.blade.php** - Global payment reports

## Usage Examples

### Get Outstanding Balance for Contractor
```php
$service = app(ContractorPaymentService::class);
$outstanding = $service->getContractorOutstandingBalance($contractorId);
```

### Record Payment After Voucher Creation
```php
$service = app(ContractorPaymentService::class);
$payment = $service->recordPayment(
    billId: $bill->id,
    voucherId: $voucher->id,
    voucherType: 'BPV',
    amount: 10000,
    remarks: 'Payment for contractor bill'
);
```

### Get Payment Summary
```php
$summary = $service->getContractorPaymentSummary($contractorId);
// Returns: total_bills, total_bill_amount, total_paid_amount, total_outstanding, payment_percentage
```

## Status Flow

```
Bill Creation
    ↓
Bill Verification (Status: Verified)
    ↓
Make Payment (If outstanding > 0)
    ↓
Create BPV/CPV Voucher
    ↓
Record Payment
    ↓
Paid = 0           → Status: Verified
Paid < Amount     → Status: Partial Paid
Paid >= Amount    → Status: Paid
```

## Outstanding Amount Calculation

```
Outstanding Amount = Bill Amount - (Sum of Posted Payments)
```

## Installation

1. Run migration:
```bash
php artisan migrate
```

2. The migration creates `contractor_bill_payments` table

3. Models, Controller, Service, and Routes are already configured

## Security Considerations

- Payments can only be recorded for verified bills
- Only pending payments can be cancelled
- Payment amounts validated against outstanding balance
- User authentication required for all payment operations
- Audit trail maintained via created_by/updated_by fields
- Soft deletes enabled for data integrity

## Integration Points

- **Existing Voucher System**: Links to BPV/CPV without duplicating logic
- **Contractor Accounts**: Uses DetailAccount model for contractor references
- **Tender Module**: Links bills to tenders
- **Audit System**: Maintains audit trail for all payment operations
- **User Module**: Tracks who created/updated payments

## Future Enhancements

- Payment schedule management
- Advance payment tracking
- Refund/adjustment processing
- Payment plan creation
- Automated payment reminders
- Bank reconciliation
- Multi-currency support
- Payment terms customization

---

**Last Updated**: May 13, 2026  
**Version**: 1.0  
**Status**: Production Ready
