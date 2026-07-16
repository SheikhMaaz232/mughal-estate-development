<?php

namespace App\Http\Controllers\PurchaseModule;

use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\GeneralJournal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PurchaseReturnDetail;
use App\Models\PurchaseReturnMaster;
use App\Services\PurchaseReturnService;
use App\Http\Requests\PurchaseModule\PurchaseReturnRequest;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;

class PurchaseReturnController extends Controller
{
    protected $purchaseReturnService;

    public function __construct(PurchaseReturnService $purchaseReturnService)
    {
        $this->purchaseReturnService = $purchaseReturnService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $purchaseReturnsListing = PurchaseReturnMaster::with('party', 'detailAccount', 'project')->search($filters)->latest()->paginate(10);

        return view('purchase-module.purchase-return.index', compact('purchaseReturnsListing'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $purchaseReturnId = PurchaseReturnMaster::max('id');
        $maxId = $purchaseReturnId ? $purchaseReturnId + 1 : 1;
        $purchaseMasterData = PurchaseMaster::where('id', $request->purchase_invoice_no)->first();
        if (!$purchaseMasterData) {
            return redirect()->back()->with('error', __('messages.purchase_not_found'));
        }
        $purchaseDetailsData = PurchaseDetail::select('product_id', 'quantity', 'price', 'amount')->where('purchase_master_id', $purchaseMasterData->id)->get();


        return view('purchase-module.purchase-return.create', compact('maxId', 'purchaseMasterData', 'purchaseDetailsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseReturnRequest $request)
    {
        try {

            $this->purchaseReturnService->store($request->validated());

            return redirect()->route('purchase-return.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // try {
        $purchaseReturnMaster = $this->purchaseReturnService->getById($id);
        $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_master_id', $id)->get();

        return view('purchase-module.purchase-return.edit', compact('purchaseReturnMaster', 'purchaseReturnDetails'));
        // } catch (\Exception $e) {
        //     return redirect()->route('purchase-return.index')->with('error', __('messages.unexpected-error'));
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseReturnRequest $request, $id)
    {
        try {
            $purchaseReturnMaster = PurchaseReturnMaster::findOrFail($id);
            $this->purchaseReturnService->update($request->validated(), $purchaseReturnMaster);

            return redirect()
                ->route('purchase-return.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while updating the purchase order.']);
        }
    }

    /**
     * Display the specified Purchase order details with related Bank Accounts.
     *
     * @param  \App\Models\PurchaseReturnMaster  $purchaseReturnMaster
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $purchaseReturn = PurchaseReturnMaster::where('id', $id)->first();
            $purchaseReturnDetails = $this->purchaseReturnService->getPurchaseDetails($purchaseReturn->id);

            return view('purchase-module.purchase-return.show', compact('purchaseReturn', 'purchaseReturnDetails'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->purchaseReturnService->delete($id);
            return redirect()->route('purchase-return.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('purchase-return.index')->with('error', __('messages.unexpected-error'));
        }
    }
    public function generate()
    {
        return view('purchase-module.purchase-return.generate');
    }


    public function updateStatus(Request $request, $id)
    {

        $request->validate([
            'status' => 'required|in:Verified,Unverified',
        ]);
        try {
            DB::beginTransaction();

            $purchaseReturnStatus = PurchaseReturnMaster::lockForUpdate()->findOrFail($id);

            // Prevent duplicate processing
            if ($purchaseReturnStatus->status === 'Verified') {
                DB::rollBack();
                return redirect()->route('purchase-return.index')
                    ->with('info', 'This Invoice already verified.');
            }

            // Update status
            $purchaseReturnStatus->status = $request->status;
            $purchaseReturnStatus->save();

            // Fetch related payments
            $purchaseReturnDetails = PurchaseReturnDetail::where('purchase_return_master_id', $purchaseReturnStatus->id)->get();
            $documentNo = 'P-R' . '-' . $purchaseReturnStatus->id;
            // Only create ledger entries if they don’t already exist
            $ledgerExists = AccountLedger::where('invoice_id', $purchaseReturnStatus->id)->where('document_number', $documentNo)->exists();
            $journalExists = GeneralJournal::where('invoice_id', $purchaseReturnStatus->id)->where('document_number', $documentNo)->exists();

            if (!$ledgerExists && !$journalExists) {
                $this->purchaseReturnService->createLedgerEntry($purchaseReturnStatus, $purchaseReturnDetails);
            }

            DB::commit();

            return redirect()->route('purchase-return.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('❌ Purchase Invoice verification failed', [
                'Purchase_return_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('purchase-return.index')
                ->with('error', 'An error occurred while verifying the booking. Please try again.');
        }
    }

    public function getItemMeasurementUnitDetail($productId)
    {
        try {
            $itemMeasurementUnit = $this->purchaseReturnService->getItemMeasurementUnit($productId);
            if ($itemMeasurementUnit) {
                return response()->json(['unit' => 'success', 'data' => $itemMeasurementUnit]);
            }
            return response()->json(['unit' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['unit' => 'fail', 'data' => []]);
        }
    }
}
