# Contractor Bills Module - Quick Start Guide

## 🚀 Getting Started

### Access the Module
1. Log in to the system
2. Go to main menu
3. Click **Contractor Bills**

### First Time Setup
Before creating bills, ensure you have:
- ✅ Construction Site created
- ✅ Tender created with:
  - Contractor Account (payment party)
  - Expense Account (cost center)
- ✅ BOQ Master created with items
- ✅ Work Order created from that BOQ
- ✅ Work Progress recorded with completed quantities

---

## 📝 Step-by-Step: Creating Your First Bill

### Step 1: Click "New Bill" Button
Located at top-right of Contractor Bills list page.

### Step 2: Fill Basic Information
```
Tender:                   [Select from dropdown]
Work Order:              [Auto-populated after tender]
Contractor Account:      [Auto-filled from tender]
Bill Date:               [Today's date default]
Remarks:                 [Optional]
```

### Step 3: Add Items
1. Once you select a Work Order, available BOQ items appear
2. For each item:
   - Select from BOQ Item dropdown
   - Enter Quantity (system shows max available)
   - Enter Rate
   - Amount auto-calculates

### Step 4: Review & Submit
- Total amount displays at bottom
- Click "Create Bill"
- System generates bill number (CB-00001, CB-00002, etc.)

---

## ✅ Verifying a Bill

### Before Verification
- Bill must be in **Draft** status
- Items must match work progress completion
- No overbilling allowed

### How to Verify
1. Open the bill from list
2. Click **Verify** button in header
3. Confirm the action
4. System will:
   - Create Journal Voucher (JV)
   - Post Debit: Tender Expense Account
   - Post Credit: Contractor Account
   - Save voucher reference
   - Update status to **Verified**

### After Verification
- Bill cannot be edited (read-only)
- Ready for payment recording
- Check "Accounting" module to see JV posted

---

## 💰 Recording Payments

### Prerequisites
- Bill must be **Verified**
- Payment voucher (BPV or CPV) must be created in Accounting module

### How to Record Payment
1. Open verified bill
2. Scroll to "Payment History" section
3. Record payment details:
   - Voucher Type (BPV/CPV)
   - Voucher ID
   - Payment Amount
4. System auto-updates bill status:
   - Partial Paid (if partial)
   - Paid (if fully paid)

---

## 🔍 Viewing & Managing Bills

### List View Features
- **Search**: By bill number, remarks, tender, contractor
- **Filter by Status**: draft, verified, partial_paid, paid, cancelled
- **Filter by Tender**: See bills for specific tender
- **Date Range**: From-To dates
- **Export**: Download as CSV

### Bill Details View
Shows:
- Bill information (number, date, status)
- Contractor and tender details
- Itemized breakdown
- Payment history
- Remaining balance

### Actions Available
Based on bill status:

| Action | Draft | Verified | Paid |
|--------|-------|----------|------|
| Edit | ✅ | ❌ | ❌ |
| Delete | ✅ | ❌ | ❌ |
| Verify | ✅ | ❌ | ❌ |
| Print | ✅ | ✅ | ✅ |
| View | ✅ | ✅ | ✅ |

---

## 📋 Validation Rules

### Overbilling Prevention
System prevents billing more than what's completed:
```
Billable Quantity = Completed Qty (Work Progress) - Already Billed Qty
```

### Status Transitions
```
Draft → Verify → Verified → (Partial Paid) → Paid
  ↓
Cancel → Cancelled
```

### Amount Validation
- Quantity must be > 0
- Rate must be ≥ 0
- Amount auto-calculated as: Quantity × Rate

---

## 🎨 Printing & Export

### Print Bill
1. Open the bill
2. Click **Print** button
3. Professional print layout shows:
   - Bill details
   - Item breakdown
   - Total amount
   - Payment history (if verified)

### Export to CSV
1. From bill list page
2. Apply filters (optional)
3. Click **Export** button
4. Download CSV file with:
   - Bill number, date, tender, contractor
   - Amount, status, paid amount

---

## 🆘 Common Questions

### Q: Can I edit a verified bill?
**A:** No. Verified bills are read-only. To make changes, cancel and recreate.

### Q: What if I overbill?
**A:** System prevents it. Check Work Progress for completed quantities.

### Q: How is the accounting entry posted?
**A:** Automatically on verification:
- **Dr**: Tender's Expense Account (defined in Tender master)
- **Cr**: Contractor Account (selected when creating bill)

### Q: Can I cancel a verified bill?
**A:** Not directly. Contact administrator to reverse JV posting.

### Q: What if quantity changes mid-work?
**A:** Update Work Progress, then create a new bill for additional work.

---

## 📊 Reporting

### Available Reports
1. **Bill List Report**: All bills with filters
2. **Outstanding Report**: Unpaid/partial paid bills
3. **Payment Report**: All payments received
4. **Contractor Ledger**: Party-wise transactions

### How to Generate
1. Go to Reports menu
2. Select Contractor Bill reports
3. Apply date range & filters
4. Export or print

---

## 🔒 Access Control

### Permissions Required
- `create-contractor-bill`: Create new bills
- `edit-contractor-bill`: Edit draft bills
- `verify-contractor-bill`: Verify bills
- `view-contractor-bill`: View bills
- `delete-contractor-bill`: Delete draft bills

### Default Access
- Admins: Full access
- Managers: Create, verify, view
- Accountants: View only (for JV verification)

---

## 🆘 Troubleshooting

### Issue: "Overbilling detected"
**Cause**: Billed quantity exceeds completed quantity  
**Solution**: 
1. Check Work Progress for actual completion
2. Reduce bill quantity
3. Or update Work Progress if already completed

### Issue: "Cannot edit verified bill"
**Cause**: Bill status is not Draft  
**Solution**:
- Verified bills are permanent
- Create a new bill for corrections/changes

### Issue: Dropdown not loading
**Cause**: AJAX request failed  
**Solution**:
1. Check browser console for errors
2. Verify Laravel is running
3. Try refreshing page

### Issue: JV not created on verification
**Cause**: Missing accounting accounts  
**Solution**:
1. Check Tender has Expense Account assigned
2. Check Contractor Account is valid
3. See application logs for details

---

## 📞 Support

For issues or clarifications:
1. Check this guide
2. See implementation documentation
3. Contact System Administrator
4. Review application logs

---

## 🎓 Learning Resources

Recommended reading:
1. **PHASE_2_IMPLEMENTATION_GUIDE.md** - Technical architecture
2. **Tender Master** - Understand accounts setup
3. **Work Progress** - Understand completion tracking
4. **General Journal** - Understand accounting posting

---

**Version**: 1.0  
**Last Updated**: May 10, 2026  
**Status**: Production Ready

