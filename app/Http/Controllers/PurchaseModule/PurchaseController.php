<?php

namespace App\Http\Controllers\PurchaseModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseModule\PurchaseInvoiceRequest;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\GoodsReceivedNoteDetail;
use App\Models\GoodsReceivedNoteMaster;
use App\Models\Item;
use App\Models\PurchaseDetail;
use App\Models\PurchaseMaster;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetails;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    protected $purchaseService;

    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    private function getMasterData()
    {
        return [
            'projects' => Cache::remember('projects_data', 3600, fn() =>
            \App\Models\Project::select('id', 'name_en', 'name_ur')->get()),

            'searchParties' => Cache::remember('parties_data', 3600, fn() =>
            \App\Models\Party::with('cast')->select('id', 'name_en', 'name_ur', 'cnic_no', 'contact_number_1', 'cast_id')->get()),

            'detailAccounts' => Cache::remember('detail_accounts_data', 3600, fn() =>
            DetailAccount::select('id', 'name_en', 'name_ur')->get()),

            'items' => Cache::remember('items_data', 3600, fn() =>
            Item::select('id', 'name_en', 'name_ur', 'measurement_unit_id')->get()),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $purchaseInvoicesListing = PurchaseMaster::with('party', 'detailAccount', 'project')->search($filters)->latest()->paginate(10);

        return view('purchase-module.purchase.index', array_merge(
            [
                'purchaseInvoicesListing' => $purchaseInvoicesListing
            ],
            $this->getMasterData()
        ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $purchaseId = PurchaseMaster::max('id');
        $maxId = $purchaseId ? $purchaseId + 1 : 1;
        $grnMaster = GoodsReceivedNoteMaster::select('id', 'purchase_order_no', 'project_id', 'party_id', 'detail_account_id', 'fare', 'supplier_bill_no', 'unloaded_by', 'total_received_quantity', 'remarks')->where('id', $request->grn_id)->first();
        $grnDetails = GoodsReceivedNoteDetail::select('product_id', 'po_quantity', 'received_qty', 'balance')->where('master_id', $grnMaster->id)->get();
        $purchaseOrderMaster = PurchaseOrder::select('id', 'gross_total', 'tax_amount', 'shipping_amount', 'other_amount', 'total_amount')->where('id', $grnMaster->purchase_order_no)->first();
        $purchaseOrderDetails = PurchaseOrderDetails::select('product_id', 'quantity', 'price', 'amount', 'detail_remarks')->where('purchase_order_master_id', $purchaseOrderMaster->id)->get()->keyBy('product_id');
        if (!$grnMaster) {
            return redirect()->back()->with('error', __('messages.grn_not_found'));
        } elseif (!$purchaseOrderMaster) {
            return redirect()->back()->with('error', __('messages.purchase_order_not_found'));
        }

        return view('purchase-module.purchase.create', array_merge(
            [
                'maxId' => $maxId,
                'grnMaster' => $grnMaster,
                'grnDetails' => $grnDetails,
                'purchaseOrderMaster' => $purchaseOrderMaster,
                'purchaseOrderDetails' => $purchaseOrderDetails
            ],
            $this->getMasterData()
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseInvoiceRequest $request)
    {
        try {
            $this->purchaseService->store($request->validated());

            return redirect()->route('purchase-invoice.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $purchaseMaster = $this->purchaseService->getById($id);
            $purchaseDetails = PurchaseDetail::where('purchase_master_id', $id)->get();

            return view('purchase-module.purchase.edit', array_merge(
                [
                    'purchaseMaster' => $purchaseMaster,
                    'purchaseDetails' => $purchaseDetails
                ],
                $this->getMasterData()
            ));
        } catch (\Exception $e) {
            return redirect()->route('purchase-invoice.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseInvoiceRequest $request, $id)
    {
        // try {
            $purchaseMaster = PurchaseMaster::findOrFail($id);
            $this->purchaseService->update($request->validated(), $purchaseMaster);

            return redirect()
                ->route('purchase-invoice.index')
                ->with('success', __('messages.record-updated'));
        // } catch (\Throwable $e) {
        //     return back()
        //         ->withInput()
        //         ->withErrors(['error' => 'Something went wrong while updating the purchase order.']);
        // }
    }

    /**
     * Display the specified Purchase order details with related Bank Accounts.
     *
     * @param  \App\Models\PurchaseMaster  $purchaseMaster
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $purchaseMaster = PurchaseMaster::where('id', $id)->first();
            $purchaseInvoiceDetails = $this->purchaseService->getPurchaseDetails($purchaseMaster->id);

            return view('purchase-module.purchase.show', array_merge(
                [
                    'purchaseMaster' => $purchaseMaster,
                    'purchaseInvoiceDetails' => $purchaseInvoiceDetails
                ],
                $this->getMasterData()
            ));
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
            $this->purchaseService->delete($id);
            return redirect()->route('purchase-invoice.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('purchase-invoice.index')->with('error', __('messages.unexpected-error'));
        }
    }
    public function generate()
    {
        return view('purchase-module.purchase.generate');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Unverified',
        ]);

        try {
            DB::beginTransaction();
            $purchaseInvoiceStatus = PurchaseMaster::lockForUpdate()->findOrFail($id);

            // Prevent duplicate processing
            if ($purchaseInvoiceStatus->status === 'Verified') {
                DB::rollBack();
                return redirect()->route('purchase-invoice.index')
                    ->with('info', 'This Invoice already verified.');
            }

            // Update status
            $purchaseInvoiceStatus->status = $request->status;
            $purchaseInvoiceStatus->save();

            // Fetch related payments
            $purchaseDetails = PurchaseDetail::where('purchase_master_id', $purchaseInvoiceStatus->id)->get();
            $documentNo = 'P-I' . '-' . $purchaseInvoiceStatus->id;
            // Only create ledger entries if they don’t already exist
            $ledgerExists = AccountLedger::where('invoice_id', $purchaseInvoiceStatus->id)->where('document_number', $documentNo)->exists();
            $journalExists = GeneralJournal::where('invoice_id', $purchaseInvoiceStatus->id)->where('document_number', $documentNo)->exists();

            if (!$ledgerExists && !$journalExists) {
                $this->purchaseService->createLedgerEntry($purchaseInvoiceStatus, $purchaseDetails);
            }

            DB::commit();

            return redirect()->route('purchase-invoice.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(' Purchase Invoice verification failed', [
                'Purchase_invoice_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('purchase-invoice.index')
                ->with('error', 'An error occurred while verifying the booking. Please try again.');
        }
    }

    public function getItemMeasurementUnitDetail($productId)
    {
        try {
            $itemMeasurementUnit = $this->purchaseService->getItemMeasurementUnit($productId);
            if ($itemMeasurementUnit) {
                return response()->json(['unit' => 'success', 'data' => $itemMeasurementUnit]);
            }
            return response()->json(['unit' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['unit' => 'fail', 'data' => []]);
        }
    }
}
