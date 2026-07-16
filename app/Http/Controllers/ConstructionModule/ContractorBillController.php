<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContractorBillRequest;
use App\Http\Requests\UpdateContractorBillRequest;
use App\Models\BOQDetail;
use App\Models\ContractorBill;
use App\Models\DetailAccount;
use App\Models\Tender;
use App\Models\WorkOrder;
use App\Services\ContractorBillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContractorBillController extends Controller
{
    protected $contractorBillService;

    public function __construct(ContractorBillService $contractorBillService)
    {
        $this->contractorBillService = $contractorBillService;
    }

    /**
     * Display a listing of contractor bills
     */
    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'tender_id' => $request->input('tender_id'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'per_page' => $request->input('per_page', 15),
        ];

        $bills = $this->contractorBillService->getAll($filters);
        $tenders = Tender::select('id', 'title_en')->get();
        $statuses = ['draft', 'verified', 'partial_paid', 'paid', 'cancelled'];

        return view('Construction-Module.contractor-bills.index', compact('bills', 'tenders', 'statuses'));
    }

    /**
     * Show the form for creating a new contractor bill
     */
    public function create(Request $request)
    {
        $workOrderId = $request->get('work_order_id');

        if (!$workOrderId) {
            return redirect()->route('work-progress.index')
                ->with('error', __('messages.work-order-required'));
        }

        $workOrderData = WorkOrder::with(['constructionSite', 'tender', 'items'])->findOrFail($workOrderId);
        $availableItems = $this->contractorBillService->getAvailableBillItems($workOrderId);

        return view('Construction-Module.contractor-bills.create', compact('workOrderData', 'availableItems'));
    }

    /**
     * Store a newly created contractor bill
     */
    public function store(StoreContractorBillRequest $request)
    {
        // try {
            // Validate items against work progress
            $errors = $this->contractorBillService->validateBillItems(
                null,
                $request->input('items'),
                $request->input('work_order_id')
            );

            if (!empty($errors)) {
                return back()->withErrors(['items' => implode(', ', $errors)])->withInput();
            }

            $bill = $this->contractorBillService->create($request->validated());

            return redirect()->route('contractor-bills.show', $bill->id)
                ->with('success', 'Contractor bill created successfully');
        // } catch (\Exception $e) {
        //     return back()->with('error', $e->getMessage())->withInput();
        // }
    }

    /**
     * Display the specified contractor bill
     */
    public function show($id)
    {
        $bill = $this->contractorBillService->getById($id);
        $tender = $bill->tender;

        return view('Construction-Module.contractor-bills.show', compact('bill', 'tender'));
    }

    /**
     * Show the form for editing the specified contractor bill
     */
    public function edit($id)
    {
        $bill = ContractorBill::with('items', 'tender', 'workOrder')
            ->findOrFail($id);

        if (!$bill->canEdit()) {
            return back()->with('error', 'Cannot edit verified bill');
        }

        $tenders = Tender::where('status', '!=', 'completed')->get();
        $workOrders = WorkOrder::get();
        $contractorAccounts = DetailAccount::get();
        $boqItems = BOQDetail::where('boq_master_id', $bill->workOrder->boq_id)->get();
        $availableItems = $this->contractorBillService->getAvailableBillItems($bill->work_order_id);

        return view('Construction-Module.contractor-bills.edit', compact(
            'bill',
            'tenders',
            'workOrders',
            'contractorAccounts',
            'boqItems',
            'availableItems'
        ));
    }

    /**
     * Update the specified contractor bill
     */
    public function update(UpdateContractorBillRequest $request, $id)
    {
        try {
            // Validate items against work progress
            $errors = $this->contractorBillService->validateBillItems(
                $id,
                $request->input('items')
            );

            if (!empty($errors)) {
                return back()->withErrors(['items' => implode(', ', $errors)])->withInput();
            }

            $bill = $this->contractorBillService->update($id, $request->validated());

            return redirect()->route('contractor-bills.show', $bill->id)
                ->with('success', 'Contractor bill updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Verify a contractor bill and create JV posting
     */
    public function verify(Request $request, $id)
    {
        try {
            $bill = $this->contractorBillService->verify($id, Auth::id());

            return redirect()->route('contractor-bills.show', $bill->id)
                ->with('success', 'Contractor bill verified and JV posted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel a contractor bill
     */
    public function cancel(Request $request, $id)
    {
        try {
            $bill = $this->contractorBillService->cancel($id);

            return redirect()->route('contractor-bills.index')
                ->with('success', 'Contractor bill cancelled successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a contractor bill (soft delete)
     */
    public function destroy($id)
    {
        try {
            $bill = ContractorBill::findOrFail($id);

            if (!$bill->canEdit()) {
                return back()->with('error', 'Can only delete draft bills');
            }

            $bill->delete();

            return redirect()->route('contractor-bills.index')
                ->with('success', 'Contractor bill deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Get BOQ items for a work order (AJAX)
     */
    public function getBoqItems($workOrderId)
    {
        try {
            $workOrder = WorkOrder::findOrFail($workOrderId);
            $boqItems = BOQDetail::where('boq_master_id', $workOrder->boq_id)
                ->with('item')
                ->get()
                ->map(function ($item) use ($workOrderId) {
                    return [
                        'id' => $item->id,
                        'description' => $item->item->name ?? $item->description,
                        'unit' => $item->unit,
                        'remaining_qty' => $this->contractorBillService->getRemainingQuantity($item->id, $workOrderId),
                        'rate' => $item->rate,
                    ];
                });

            return response()->json($boqItems);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get remaining quantity for a BOQ item (AJAX)
     */
    public function getRemainingQuantity(Request $request)
    {
        try {
            $boqItemId = $request->input('boq_item_id');
            $workOrderId = $request->input('work_order_id');
            $billId = $request->input('bill_id');

            $remaining = $this->contractorBillService->getRemainingQuantity(
                $boqItemId,
                $workOrderId,
                $billId
            );

            return response()->json(['remaining_qty' => $remaining]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get work orders for a tender (AJAX)
     */
    public function getWorkOrders($tenderId)
    {
        try {
            $workOrders = WorkOrder::where('tender_id', $tenderId)
                ->select('id', 'id as text')
                ->get();

            return response()->json($workOrders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Print contractor bill
     */
    public function print($id)
    {
        $bill = $this->contractorBillService->getById($id);

        return view('Construction-Module.contractor-bills.print', compact('bill'));
    }

    /**
     * Export contractor bills to CSV
     */
    public function export(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'tender_id' => $request->input('tender_id'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'per_page' => 10000,
        ];

        $bills = $this->contractorBillService->getAll($filters);

        $filename = 'contractor-bills-' . now()->format('Y-m-d-H-i-s') . '.csv';
        $handle = fopen('php://memory', 'r+');

        // Add headers
        fputcsv($handle, [
            'Bill No',
            'Bill Date',
            'Tender',
            'Contractor',
            'Amount',
            'Status',
        ]);

        // Add data
        foreach ($bills as $bill) {
            fputcsv($handle, [
                $bill->bill_no,
                $bill->bill_date->format('Y-m-d'),
                $bill->tender->title_en,
                $bill->contractorAccount->name,
                $bill->amount,
                $bill->status,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }
}
