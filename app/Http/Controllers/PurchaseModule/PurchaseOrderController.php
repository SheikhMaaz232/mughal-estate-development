<?php

namespace App\Http\Controllers\PurchaseModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseModule\PurchaseOrderRequest;
use App\Models\DetailAccount;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetails;
use App\Services\PurchaseOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class PurchaseOrderController extends Controller
{

    protected $purchaseOrderService;

    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
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
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $purchaseOrderListing = PurchaseOrder::with('party', 'detailAccount')->search($filters)->latest()->paginate(10);

        return view('purchase-module.purchase-order.index', array_merge(
            [
                'purchaseOrderListing' => $purchaseOrderListing
            ],
            $this->getMasterData()
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchaseOrderId = PurchaseOrder::max('id');

        $maxId = $purchaseOrderId ? $purchaseOrderId + 1 : 1;

        return view('purchase-module.purchase-order.create', array_merge(
            [
                'maxId' => $maxId
            ],
            $this->getMasterData()
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseOrderRequest $request)
    {

        try {
            $data = $request->all();

            app(PurchaseOrderService::class)->create($data);

            return redirect()->route('purchase-order.index')->with('success', __('messages.record-saved'));
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
        $purchaseOrder = $this->purchaseOrderService->getById($id);
        $purchaseOrderDetails = PurchaseOrderDetails::where('purchase_order_master_id', $id)->get();

        // Get items for the selected project (for dropdowns)
        $projectId = $purchaseOrder->project_id;
        $itemsData = Item::whereHas('subSubSubHead', function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })->get();

        return view('purchase-module.purchase-order.edit', array_merge(
            [
                'purchaseOrder' => $purchaseOrder,
                'purchaseOrderDetails' => $purchaseOrderDetails,
                'itemsData' => $itemsData
            ],
            $this->getMasterData()
        ));
        // } catch (\Exception $e) {
        //     return redirect()->route('purchase-order.index')->with('error', __('messages.unexpected-error'));
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $this->purchaseOrderService->update($purchaseOrder, $request->validated());

            return redirect()
                ->route('purchase-order.index')
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
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(PurchaseOrder $purchaseOrder)
    {

        try {
            $purchaseOrderDetails = $this->purchaseOrderService->getPurchaseOrderDetails($purchaseOrder->id);

            return view('purchase-module.purchase-order.show', array_merge(
                [
                    'purchaseOrder' => $purchaseOrder,
                    'purchaseOrderDetails' => $purchaseOrderDetails
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
            $this->purchaseOrderService->delete($id);
            return redirect()->route('purchase-order.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('purchase-order.index')->with('error', __('messages.unexpected-error'));
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Unverified',
        ]);

        try {
            $purchaseOrderStatus = PurchaseOrder::lockForUpdate()->findOrFail($id);

            // Update status
            $purchaseOrderStatus->status = $request->status;
            $purchaseOrderStatus->save();

            return redirect()->route('purchase-order.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {

            return redirect()->route('purchase-order.index')
                ->with('error', 'An error occurred while verifying the booking. Please try again.');
        }
    }

    public function getItemMeasurementUnitDetail($productId)
    {
        try {
            $itemMeasurementUnit = $this->purchaseOrderService->getItemMeasurementUnit($productId);
            if ($itemMeasurementUnit) {
                return response()->json(['unit' => 'success', 'data' => $itemMeasurementUnit]);
            }
            return response()->json(['unit' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['unit' => 'fail', 'data' => []]);
        }
    }

    public function getDetailAccounts(Request $request)
    {
        $partyId = $request->party_id;
        $projectId = $request->project_id;

        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $query = DetailAccount::query();

        if (!empty($partyId)) {
            $query->where('party_id', $partyId);
        }

        if (!empty($projectId)) {
            $query->whereHas('subSubSubHead', function ($q) use ($projectId) {
                $q->where('project_id', $projectId);
            });
        }

        $detailAccounts = $query->pluck($field, 'id');

        return response()->json([
            'status' => 'success',
            'data' => $detailAccounts
        ]);
    }

    public function getProjectItems($projectId)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';

        $items = Item::whereHas('subSubSubHead', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->pluck($field, 'id');

        return response()->json([
            'status' => 'success',
            'data' => $items
        ]);
    }
}
