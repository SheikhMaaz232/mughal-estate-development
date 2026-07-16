<?php

namespace App\Http\Controllers\PurchaseModule;

use App\Services\GRNService;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderDetails;
use App\Models\GoodsReceivedNoteMaster;
use App\Http\Requests\PurchaseModule\GRNRequest;
use App\Models\GoodsReceivedNoteDetail;

class GRNController extends Controller
{

    protected $grnService;

    public function __construct(GRNService $grnService)
    {
        $this->grnService = $grnService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $grnsListing = GoodsReceivedNoteMaster::with('party', 'detailAccount', 'project')->search($filters)->latest()->paginate(10);

        return view('purchase-module.grn.index', compact('grnsListing'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $grnId = GoodsReceivedNoteMaster::max('id');
        $maxId = $grnId ? $grnId + 1 : 1;
        $purchaseOrderMaster = PurchaseOrder::select('id', 'project_id', 'party_id', 'detail_account_id', 'remarks')->where('id', $request->purchase_order_id)->first();
        if (!$purchaseOrderMaster) {
            return redirect()->back()->with('error', __('messages.purchase_order_not_found'));
        }
        $grnMaster = GoodsReceivedNoteMaster::select('total_po_quantity', 'total_received_quantity')->where('purchase_order_no', $request->purchase_order_id)->first();
        if ($grnMaster && $grnMaster->total_po_quantity == $grnMaster->total_received_quantity) {
            return redirect()->back()->with('warning', __('messages.order_quantity_completed'));
        }
        $purchaseOrderDetails = PurchaseOrderDetails::select('product_id', 'quantity', 'detail_remarks')->where('purchase_order_master_id', $purchaseOrderMaster->id)->get();

        return view('purchase-module.grn.create', compact('maxId', 'purchaseOrderMaster', 'purchaseOrderDetails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GRNRequest $request)
    {
        try {
            $data = $request->all();

            app(grnService::class)->create($data);

            return redirect()->route('grn.index')->with('success', __('messages.record-saved'));
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
        $grnMaster = $this->grnService->getById($id);
        $grnDetails = GoodsReceivedNoteDetail::where('master_id', $id)->get();

        return view('purchase-module.grn.edit', compact('grnMaster', 'grnDetails'));
        // } catch (\Exception $e) {
        //     return redirect()->route('grn.index')->with('error', __('messages.unexpected-error'));
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GRNRequest $request, $id)
    {
        // try {
        $grnMasterData = GoodsReceivedNoteMaster::where('id', $id)->first();
        $this->grnService->update($grnMasterData, $request->validated());

        return redirect()
            ->route('grn.index')
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
     * @param  \App\Models\GoodsReceivedNoteMaster  $grnMaster
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        try {
            $goodsReceivedNoteMaster = GoodsReceivedNoteMaster::where('id', $id)->first();
            $goodsReceivedNoteDetails = $this->grnService->getGoodsReceivedNoteDetails($goodsReceivedNoteMaster->id);

            return view('purchase-module.grn.show', compact('goodsReceivedNoteMaster', 'goodsReceivedNoteDetails'));
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
            $this->grnService->delete($id);
            return redirect()->route('grn.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('grn.index')->with('error', __('messages.unexpected-error'));
        }
    }
    public function generate()
    {
        return view('purchase-module.grn.generate');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Unverified',
        ]);

        try {
            $grnStatus = GoodsReceivedNoteMaster::lockForUpdate()->findOrFail($id);

            // ✅ Update status
            $grnStatus->status = $request->status;
            $grnStatus->save();

            return redirect()->route('grn.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {

            return redirect()->route('grn.index')
                ->with('error', 'An error occurred while verifying the booking. Please try again.');
        }
    }

    public function getItemMeasurementUnitDetail($productId)
    {
        try {
            $itemMeasurementUnit = $this->grnService->getItemMeasurementUnit($productId);
            if ($itemMeasurementUnit) {
                return response()->json(['unit' => 'success', 'data' => $itemMeasurementUnit]);
            }
            return response()->json(['unit' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['unit' => 'fail', 'data' => []]);
        }
    }
}
