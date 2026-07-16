<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConstructionModule\StoreWorkProgressRequest;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use App\Models\WorkProgress;
use App\Services\WorkProgressService;
use Illuminate\Http\Request;

class WorkProgressController extends Controller
{

    protected $service;

    public function __construct(WorkProgressService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        $search = $request->search;

        $progresses = WorkProgress::with('workOrder')
            ->search($search)
            ->latest()
            ->paginate(10);

        return view('Construction-Module.work-progress.index', compact('progresses', 'search'));
    }

    public function create(Request $request)
    {
        $workOrderId = $request->get('work_order_id');

        if (!$workOrderId) {
            return redirect()->route('work-progress.index')
                ->with('error', __('messages.work-order-required'));
        }

        $workOrderData = WorkOrder::with(['constructionSite', 'tender', 'items'])->findOrFail($workOrderId);
        $availableItems = $this->service->getAvailableWorkOrderItems($workOrderId);

        return view('Construction-Module.work-progress.create', compact('workOrderData', 'availableItems'));
    }

    public function store(StoreWorkProgressRequest $request)
    {
        try {
            $this->service->store($request->all());

            return redirect()->route('work-progress.index')
                ->with('success', __('messages.created_success'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $progress = WorkProgress::with('workOrder', 'details')->findOrFail($id);
        return view('Construction-Module.work-progress.show', compact('progress'));
    }

    public function edit($id)
    {
        $progress = WorkProgress::with('details')->findOrFail($id);

        $workOrderData = WorkOrder::with(['constructionSite', 'tender', 'items'])
            ->findOrFail($progress->work_order_id);

        $availableItems = $this->service->getAvailableWorkOrderItemsEditCase($progress->work_order_id, $progress->id);

        return view('Construction-Module.work-progress.edit', compact(
            'progress',
            'workOrderData',
            'availableItems'
        ));
    }

    public function update(StoreWorkProgressRequest $request, $id)
    {
        try {
            $this->service->update($id, $request->all());

            return redirect()->route('work-progress.index')
                ->with('success', __('messages.updated_success'));
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        WorkProgress::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', __('messages.deleted_success'));
    }

    public function getItems($workOrderId)
    {
        $items = WorkOrderItem::with('item')
            ->where('work_order_id', $workOrderId)
            ->get();

        return response()->json($items);
    }
}
