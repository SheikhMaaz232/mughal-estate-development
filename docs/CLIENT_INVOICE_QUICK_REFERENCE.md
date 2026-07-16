# Client Invoice Module - Quick Reference

## 🚀 Quick Start

### Migration
```bash
php artisan migrate
```

### Routes
- List: `GET /client-invoices`
- Create: `GET /client-invoices/create`
- Store: `POST /client-invoices`
- Show: `GET /client-invoices/{invoice}`
- Edit: `GET /client-invoices/{invoice}/edit`
- Update: `PUT /client-invoices/{invoice}`
- Delete: `DELETE /client-invoices/{invoice}`
- Verify: `POST /client-invoices/{invoice}/verify`
- Print: `GET /client-invoices/{invoice}/print`
- Get Clients: `GET /client-invoices/api/clients/{tenderId}`

---

## 📦 Module Structure

```
app/
├── Models/
│   └── ClientInvoice.php
├── Http/
│   ├── Controllers/
│   │   └── Registration/
│   │       └── ClientInvoiceController.php
│   └── Requests/
│       ├── StoreClientInvoiceRequest.php
│       └── UpdateClientInvoiceRequest.php
├── Services/
│   └── ClientInvoiceService.php
│
database/
└── migrations/
    └── 2026_05_13_create_client_invoices_table.php

resources/views/
└── registration/
    └── client-invoices/
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        ├── show.blade.php
        └── print.blade.php
```

---

## 🔑 Key Methods

### ClientInvoiceService

```php
// Get all invoices
$invoices = $service->getAll([
    'search' => 'INV-001',
    'status' => 'draft',
    'tender_id' => 1,
    'from_date' => '2026-01-01',
    'to_date' => '2026-12-31',
    'per_page' => 15
]);

// Get single invoice
$invoice = $service->getById($id);

// Store new
$invoice = $service->store($data);

// Update
$invoice = $service->update($invoice, $data);

// Verify and create JV
$invoice = $service->verify($invoice);

// Cancel
$invoice = $service->cancel($invoice);

// Delete
$service->delete($invoice);

// Get for print
$invoice = $service->getInvoiceForPrint($id);

// Get clients for tender
$clients = $service->getAvailableClients($tenderId);
```

### ClientInvoice Model

```php
// Check if can be edited
if ($invoice->canBeEdited()) { }

// Check if can be verified
if ($invoice->canBeVerified()) { }

// Check if already verified
if ($invoice->isVerified()) { }

// Check if JV posted
if ($invoice->isJVPosted()) { }

// Search with filters
$invoices = ClientInvoice::search($filters)->get();
```

---

## 🎯 Workflow States

```
[DRAFT] --verify--> [VERIFIED] --post--> [POSTED]
  ↓
  cancel/delete (only from draft)
  
[VERIFIED] can transition to:
  - PARTIAL_RECEIVED
  - RECEIVED
  - CANCELLED (if no JV posted)
```

---

## 💾 Data Relations

```
ClientInvoice
├── Tender (belongsTo)
├── Client/Party (belongsTo)
├── JournalVoucher (belongsTo)
├── VerifiedBy User (belongsTo)
├── CreatedBy User (belongsTo)
└── UpdatedBy User (belongsTo)
```

---

## ✅ Validations

### Store
- `tender_id`: required, exists in tenders table
- `client_id`: required, exists in parties table
- `invoice_no`: required, unique, max 50
- `invoice_date`: required, date format
- `amount`: required, numeric, min 0.01
- `remarks`: optional, max 500

### Update
- Same as Store, with unique check excluding current ID

---

## 🔐 Security Rules

1. **Edit Restriction**
   - Only draft invoices can be edited
   - Controller checks `canBeEdited()`
   - Soft delete audit trail maintained

2. **Verification**
   - Only draft invoices can be verified
   - Automatic JV creation prevents duplicates
   - Status tracked: draft → verified

3. **Accounting**
   - JV posting creates account ledger entries
   - General journal entries created
   - Debit/credit balance maintained
   - Voucher ID stored for reference

4. **Deletion**
   - Draft invoices: soft delete
   - Verified invoices: cannot delete
   - Admin: force delete available
   - Audit trail preserved

---

## 🧪 Testing

```php
// Create test invoice
$invoice = ClientInvoice::create([
    'tender_id' => 1,
    'client_id' => 5,
    'invoice_no' => 'TEST-001',
    'invoice_date' => now()->toDateString(),
    'amount' => 50000,
    'status' => 'draft'
]);

// Test service
$service = app(ClientInvoiceService::class);
$verified = $service->verify($invoice);

// Check JV was created
$this->assertNotNull($verified->journal_voucher_id);
$this->assertEquals('verified', $verified->status);

// Check account ledger entries
$ledgerEntries = AccountLedger::where('invoice_id', $verified->journal_voucher_id)->get();
$this->assertCount(2, $ledgerEntries);
```

---

## 🎨 Template Usage

### Index Page
```blade
@forelse ($invoices as $invoice)
    <tr>
        <td>{{ $invoice->invoice_no }}</td>
        <td>{{ $invoice->tender->title_en }}</td>
        <td>{{ $invoice->client->name_en }}</td>
        <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
        <td>{{ number_format($invoice->amount, 2) }}</td>
        <td>
            <span class="badge badge-{{ 
                $invoice->status === 'verified' ? 'success' : 'warning' 
            }}">
                {{ $invoice->status }}
            </span>
        </td>
    </tr>
@endforelse
```

### Show Page
```blade
{{ $invoice->invoice_no }}
{{ $invoice->tender->title_en }}
{{ $invoice->client->name_en }}
{{ $invoice->invoice_date->format('d M Y') }}
{{ number_format($invoice->amount, 2) }}
{{ $invoice->isJVPosted() ? 'JV-' . $invoice->journal_voucher_id : 'Not Posted' }}
```

---

## 🚨 Common Issues

### Issue: Can't verify invoice
**Cause**: Status is not draft or JV already exists
**Solution**: Check `canBeVerified()` method

### Issue: Can't edit verified invoice
**Cause**: Status is not draft
**Solution**: This is by design - only draft invoices can be edited

### Issue: JV not posting
**Cause**: Revenue account not configured on tender
**Solution**: Add `revenue_account_id` to tender

### Issue: Client not showing in dropdown
**Cause**: Client account doesn't exist in detail_accounts
**Solution**: Create detail account for client first

---

## 📊 Accounting Entries

### When Invoice is Verified

```
Journal Voucher Created:
├── Voucher No: CI-{date}-{sequence}
├── Voucher Date: invoice_date
├── Description: Client Invoice: {invoice_no}
│
├── Entry 1 (Debit):
│   ├── Account: Client Detail Account
│   ├── Amount: invoice amount
│   ├── Description: Invoice: {invoice_no}
│   └── Document: INV-{invoice_no}
│
└── Entry 2 (Credit):
    ├── Account: Tender Revenue Account
    ├── Amount: invoice amount
    ├── Description: Invoice: {invoice_no}
    └── Document: INV-{invoice_no}

Account Ledger Entries:
├── Debit: Client Account (AR)
└── Credit: Revenue Account

General Journal Entries:
├── Debit: Client Account (AR)
└── Credit: Revenue Account
```

---

## 📈 Scaling Considerations

1. **Batch Processing**: Consider implementing batch verification for multiple invoices
2. **API Pagination**: Current implementation supports large datasets
3. **Archive**: Implement archive process for old invoices
4. **Reporting**: Create views for consolidated invoicing reports

---

## 🔗 Integration Checklist

- [ ] Database migration executed
- [ ] Routes registered in web.php
- [ ] Service container auto-resolution working
- [ ] Language files translated
- [ ] Tender revenue accounts configured
- [ ] User permissions assigned
- [ ] Testing completed
- [ ] Documentation reviewed

---

**Version**: 1.0  
**Last Updated**: May 13, 2026  
**Framework**: Laravel 12
