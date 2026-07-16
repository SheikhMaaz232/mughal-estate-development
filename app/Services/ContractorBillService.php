<?php

namespace App\Services;

use App\Models\BOQDetail;
use App\Models\ContractorBill;
use App\Models\ContractorBillItem;
use App\Models\ContractorBillPayment;
use App\Models\WorkOrder;
use App\Models\WorkProgressDetail;
use App\Services\ContractorBillPostingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractorBillService
{
    protected $postingService;

    public function __construct(ContractorBillPostingService $postingService)
    {
        $this->postingService = $postingService;
    }

    /**
     * Get a single bill by ID
     */
    public function getById($id)
    {
        return ContractorBill::with('items.boqItem', 'payments', 'tender', 'contractorAccount', 'journalVoucher')
            ->findOrFail($id);
    }

    /**
     * Get all bills with filters
     */
    public function getAll($filters = [])
    {
        return ContractorBill::query()
            ->with('tender', 'contractorAccount', 'items')
            ->search($filters['search'] ?? null)
            ->filterByStatus($filters['status'] ?? null)
            ->filterByTender($filters['tender_id'] ?? null)
            ->filterByDate(
                $filters['from_date'] ?? null,
                $filters['to_date'] ?? null
            )
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get available BOQ items for a work order
     */
    public function getAvailableBillItems($workOrderId)
    {
        $workOrder = WorkOrder::findOrFail($workOrderId);

        $boqItems = BOQDetail::with('item')
            ->where('boq_master_id', $workOrder->boq_id)
            ->get();

        return $boqItems->map(function ($detail) use ($workOrderId) {
            $completedQty = WorkProgressDetail::where('item_id', $detail->item_id)
                ->whereHas('workProgress', function ($q) use ($workOrderId) {
                    $q->where('work_order_id', $workOrderId);
                })
                ->sum('completed_qty');

            $billedQty = ContractorBillItem::where('boq_item_id', $detail->id)
                ->whereHas('contractorBill', function ($q) use ($workOrderId) {
                    $q->where('work_order_id', $workOrderId);
                })
                ->sum('quantity');

            $remainingQty = max(0, $completedQty - $billedQty);

            return [
                'id' => $detail->id,
                'item_name_en' => $detail->item->name_en ?? $detail->item->name ?? '-',
                'item_name_ur' => $detail->item->name_ur ?? $detail->item->name ?? '-',
                'unit_en' => $detail->item->measurementUnit->name_en ?? 'N/A',
                'unit_ur' => $detail->item->measurementUnit->name_ur ?? 'N/A',
                'rate' => $detail->rate,
                'completed_quantity' => $completedQty,
                'billed_quantity' => $billedQty,
                'remaining_quantity' => $remainingQty,
            ];
        })->filter(function ($item) {
            return $item['remaining_quantity'] > 0;
        })->values();
    }

    /**
     * Create a new contractor bill with items
     */
    public function create(array $data): ContractorBill
    {
        return DB::transaction(function () use ($data) {
            // Generate bill number
            $billNo = $this->generateBillNumber();

            // Create the bill
            $bill = ContractorBill::create([
                'tender_id' => $data['tender_id'],
                'work_order_id' => $data['work_order_id'],
                'contractor_account_id' => $data['contractor_account_id'],
                'bill_no' => $billNo,
                'bill_date' => $data['bill_date'],
                'total_amount' => 0, // Will be calculated
                'remarks' => $data['remarks'] ?? null,
                'status' => 'draft',
            ]);

            // Create items and calculate total
            $totalAmount = 0;
            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $itemData) {
                    $item = ContractorBillItem::create([
                        'contractor_bill_id' => $bill->id,
                        'boq_item_id' => $itemData['boq_item_id'],
                        'quantity' => $itemData['quantity'],
                        'rate' => $itemData['rate'],
                        'amount' => $itemData['quantity'] * $itemData['rate'],
                    ]);
                    $totalAmount += $item->amount;
                }
            }

            // Update bill amount
            $bill->update(['total_amount' => $totalAmount]);

            return $bill;
        });
    }

    /**
     * Update an existing contractor bill (only if draft)
     */
    public function update($id, array $data): ContractorBill
    {
        $bill = ContractorBill::findOrFail($id);

        if (!$bill->canEdit()) {
            throw new \Exception('Cannot edit verified bill');
        }

        return DB::transaction(function () use ($bill, $data) {
            // Update bill details
            $bill->update([
                'bill_date' => $data['bill_date'] ?? $bill->bill_date,
                'remarks' => $data['remarks'] ?? $bill->remarks,
            ]);

            // Update items if provided
            if (isset($data['items']) && is_array($data['items'])) {
                // Delete existing items
                $bill->items()->delete();

                // Create new items
                $totalAmount = 0;
                foreach ($data['items'] as $itemData) {
                    $item = ContractorBillItem::create([
                        'contractor_bill_id' => $bill->id,
                        'boq_item_id' => $itemData['boq_item_id'],
                        'quantity' => $itemData['quantity'],
                        'rate' => $itemData['rate'],
                        'amount' => $itemData['quantity'] * $itemData['rate'],
                    ]);
                    $totalAmount += $item->amount;
                }

                // Update bill amount
                $bill->update(['total_amount' => $totalAmount]);
            }

            return $bill;
        });
    }

    /**
     * Verify a contractor bill and create JV posting
     *
     * Creates:
     * Dr Tender Expense Account (from tender.expense_account_id)
     * Cr Contractor Account (from work_order.contractor_account_id)
     */
    public function verify($id, $userId = null): ContractorBill
    {
        $bill = ContractorBill::findOrFail($id);
        return $this->postingService->postBill($bill, $userId);
    }

    /**
     * Add a payment to the contractor bill
     */
    public function addPayment($billId, $voucherId, $voucherType, $amount): ContractorBillPayment
    {
        $bill = ContractorBill::findOrFail($billId);

        if (!$bill->isVerified()) {
            throw new \Exception('Bill must be verified before payment');
        }

        return DB::transaction(function () use ($bill, $voucherId, $voucherType, $amount) {
            // Create payment record
            $payment = ContractorBillPayment::create([
                'contractor_bill_id' => $bill->id,
                'voucher_id' => $voucherId,
                'voucher_type' => $voucherType,
                'amount' => $amount,
            ]);

            // Update bill status
            $bill->updateBillStatus();

            return $payment;
        });
    }

    /**
     * Cancel a contractor bill (only if draft)
     */
    public function cancel($id): ContractorBill
    {
        $bill = ContractorBill::findOrFail($id);

        if ($bill->status !== 'draft') {
            throw new \Exception('Can only cancel draft bills');
        }

        $bill->update(['status' => 'cancelled']);
        return $bill;
    }

    /**
     * Generate unique bill number
     */
    protected function generateBillNumber(): string
    {
        $latestBill = ContractorBill::orderBy('id', 'desc')
            ->withTrashed()
            ->first();

        $nextNumber = ($latestBill ? intval(substr($latestBill->bill_no, -5)) : 0) + 1;
        return 'CB-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Validate bill items against work progress
     */
    public function validateBillItems($billId, $items, $workOrderId = null)
    {
        // dd($billId, $items, $workOrderId);
        if ($billId) {
            $bill = ContractorBill::findOrFail($billId);
            $workOrderId = $bill->work_order_id;
        }

        if (!$workOrderId) {
            throw new \Exception('Work order ID is required for validation');
        }

        $errors = [];

        $seenBoqItems = [];

        foreach ($items as $index => $item) {
            $boqItemId = $item['boq_item_id'] ?? null;
            $quantity = $item['quantity'] ?? 0;

            if (!$boqItemId) {
                $errors[$index] = 'BOQ Item is required';
                continue;
            }

            if (in_array($boqItemId, $seenBoqItems)) {
                $errors[$index] = 'Duplicate BOQ item is not allowed';
                continue;
            }

            $seenBoqItems[] = $boqItemId;

            // Get the BOQ item to find the item_id
            $boqItem = BOQDetail::find($boqItemId);
            if (!$boqItem) {
                $errors[$index] = 'Invalid BOQ Item';
                continue;
            }

            // Get completed quantity from work progress
            $completedQty = WorkProgressDetail::where('item_id', $boqItem->item_id)
                ->whereHas('workProgress', function ($q) use ($workOrderId) {
                    $q->where('work_order_id', $workOrderId);
                })
                ->sum('completed_qty');

            // Get already billed quantity
            $billedQty = ContractorBillItem::where('boq_item_id', $boqItemId)
                ->whereHas('contractorBill', function ($q) use ($workOrderId, $billId) {
                    $q->where('work_order_id', $workOrderId);
                    if ($billId) {
                        $q->where('id', '!=', $billId);
                    }
                })
                ->sum('quantity');

            // Check if overbilling
            if (($billedQty + $quantity) > $completedQty) {
                $errors[$index] = __('messages.overbilling-detected', [
                    'completed' => $completedQty,
                    'billed' => $billedQty,
                    'requested' => $quantity
                ]);
            }
        }

        return $errors;
    }

    /**
     * Get remaining quantity for a BOQ item in a work order
     */
    public function getRemainingQuantity($boqItemId, $workOrderId, $excludeBillId = null)
    {
        // Get the BOQ item
        $boqItem = BOQDetail::find($boqItemId);
        if (!$boqItem) {
            return 0;
        }

        // Get completed quantity
        $completedQty = WorkProgressDetail::where('item_id', $boqItem->item_id)
            ->whereHas('workProgress', function ($q) use ($workOrderId) {
                $q->where('work_order_id', $workOrderId);
            })
            ->sum('completed_qty');

        // Get billed quantity
        $billedQty = ContractorBillItem::where('boq_item_id', $boqItemId)
            ->whereHas('contractorBill', function ($q) use ($workOrderId, $excludeBillId) {
                $q->where('work_order_id', $workOrderId);
                if ($excludeBillId) {
                    $q->where('id', '!=', $excludeBillId);
                }
            })
            ->sum('quantity');

        return max(0, $completedQty - $billedQty);
    }
}
