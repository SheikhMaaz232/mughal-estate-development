<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConstructionModule\StoreWorkOrderRequest;
use App\Http\Requests\ConstructionModule\UpdateWorkOrderRequest;
use App\Models\WorkOrder;
use App\Models\BOQMaster;
use App\Models\BOQDetail;
use App\Services\WorkOrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkOrderController extends Controller
{
    protected $service;

    public function __construct(WorkOrderService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of work orders
     */
    public function index(Request $request)
    {
        $constructionSiteId = $request->get('constructionSiteId');
        $tenderId = $request->get('tenderId');
        $status = $request->get('status');

        $query = WorkOrder::query();

        $query->filterByConstructionSite($constructionSiteId)
            ->filterByTender($tenderId)
            ->filterByStatus($status)
            ->search($request->get('search'));

        $workOrdersListing = $query->with(['constructionSite', 'tender', 'boqMaster'])
            ->latest()
            ->paginate(10);

        return view('Construction-Module.work-order.index', compact('workOrdersListing', 'constructionSiteId', 'tenderId', 'status'));
    }

    /**
     * Show BOQ selection form for creating a new work order
     */
    public function selectBoq()
    {
        $boqs = BOQMaster::with(['constructionSite', 'tender'])
            ->latest()
            ->get()
            ->groupBy('construction_site_id');

        return view('Construction-Module.work-order.select-boq', compact('boqs'));
    }

    /**
     * Show the form for creating a new work order
     */
    public function create(Request $request)
    {
        $boqId = $request->get('boq_id');

        if (!$boqId) {
            return redirect()->route('boq-masters.index')
                ->with('error', __('messages.boq-required'));
        }

        $boqMaster = BOQMaster::with(['constructionSite', 'tender', 'details.item'])->findOrFail($boqId);
        $availableItems = $this->service->getAvailableBOQItems($boqId);

        return view('Construction-Module.work-order.create', compact('boqMaster', 'availableItems'));
    }

    /**
     * Store a newly created work order in storage
     */
    public function store(StoreWorkOrderRequest $request)
    {
        // try {
        //     DB::beginTransaction();
            $workOrder = $this->service->create($request->validated());

            // DB::commit();

            return redirect()->route('work-orders.show', $workOrder->id)
                ->with('success', __('messages.work-order-created-successfully'));
        // } catch (Exception $e) {
        //     DB::rollBack();

        //     Log::error('Work Order Creation Error: ' . $e->getMessage(), [
        //         'file' => $e->getFile(),
        //         'line' => $e->getLine(),
        //     ]);

        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', $e->getMessage());
        // }
    }

    /**
     * Display the specified work order
     */
    public function show($id)
    {
        $workOrder = WorkOrder::with([
            'constructionSite',
            'tender',
            'boqMaster',
            'items.item',
        ])->findOrFail($id);

        $boqDetails = BOQDetail::where('boq_master_id', $workOrder->boq_id)
            ->get()
            ->keyBy('item_id');

        return view('Construction-Module.work-order.show', compact('workOrder', 'boqDetails'));
    }

    /**
     * Show the form for editing the specified work order
     */
    public function edit($id)
    {
        $workOrder = WorkOrder::with(['constructionSite', 'tender', 'boqMaster', 'items.boqItem.item'])
            ->findOrFail($id);

        $availableItems = $this->service->getAvailableBOQItems($workOrder->boq_id);

        return view('Construction-Module.work-order.edit', compact('workOrder', 'availableItems'));
    }

    /**
     * Update the specified work order in storage
     */
    public function update(UpdateWorkOrderRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $workOrder = $this->service->update($request->validated(), $id);

            DB::commit();

            return redirect()->route('work-orders.show', $workOrder->id)
                ->with('success', __('messages.work-order-updated-successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Work Order Update Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'work_order_id' => $id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified work order from storage
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $workOrder = WorkOrder::findOrFail($id);
            $constructionSiteId = $workOrder->construction_site_id;

            $this->service->delete($id);

            DB::commit();

            return redirect()->route('work-orders.index', ['constructionSiteId' => $constructionSiteId])
                ->with('success', __('messages.work-order-deleted-successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Work Order Deletion Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'work_order_id' => $id,
            ]);

            return redirect()->back()
                ->with('error', __('messages.an-error-occurred'));
        }
    }

    /**
     * Get available BOQ items for AJAX
     */
    public function getAvailableItems(Request $request)
    {
        $boqId = $request->get('boq_id');

        if (!$boqId) {
            return response()->json(['error' => __('messages.boq-required')], 400);
        }

        try {
            $availableItems = $this->service->getAvailableBOQItems($boqId);
            return response()->json(['data' => $availableItems]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Get remaining quantity for a BOQ item
     */
    public function getRemainingQuantity(Request $request)
    {
        $boqId = $request->get('boq_id');
        $boqItemId = $request->get('boq_item_id');

        if (!$boqId || !$boqItemId) {
            return response()->json(['error' => __('messages.invalid-parameters')], 400);
        }

        try {
            $remainingQuantity = $this->service->getRemainingQuantity($boqId, $boqItemId);
            return response()->json(['data' => ['remaining_quantity' => $remainingQuantity]]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getByTender(Request $request, $tenderId)
    {
        $lang = $request->lang ?? app()->getLocale();

        $orders = WorkOrder::where('tender_id', $tenderId)->get()->map(function ($order) use ($lang) {

            // ✅ Status translation map
            $statusMap = [
                'pending' => [
                    'en' => 'Pending',
                    'ur' => 'زیر التواء'
                ],
                'in_progress' => [
                    'en' => 'In Progress',
                    'ur' => 'جاری ہے'
                ],
                'completed' => [
                    'en' => 'Completed',
                    'ur' => 'مکمل'
                ],
            ];

            return [
                'id' => $order->id,
                'title' => $lang === 'ur' ? $order->description_ur : $order->description_en,
                'status' => $statusMap[$order->status][$lang] ?? $order->status,
                'amount' => $order->total_amount,
            ];
        });

        return response()->json($orders);
    }
}
