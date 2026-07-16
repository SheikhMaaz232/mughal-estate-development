<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConstructionModule\StoreBOQMasterRequest;
use App\Http\Requests\ConstructionModule\UpdateBOQMasterRequest;
use App\Models\BOQMaster;
use App\Models\Item;
use App\Models\Tender;
use App\Services\BOQMasterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class BOQMasterController extends Controller
{
    protected $service;

    public function __construct(BOQMasterService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $constructionSiteId = request('constructionSiteId');
        $tenderId = request('tenderId');

        $query = BOQMaster::query();

        // Apply filters using scopes
        $query->filterByConstructionSite($constructionSiteId)
            ->filterByTender($tenderId)
            ->search(request('search'));

        $boqs = $query->with(['constructionSite', 'tender'])->latest()->paginate(10);

        return view('Construction-Module.boq.index', compact('boqs', 'constructionSiteId', 'tenderId'));
    }

    public function create(Request $request)
    {
        $tenderId = $request->get('id');

        if (!$tenderId) {
            return redirect()->route('boq-masters.index')
                ->with('error', __('messages.tender-required'));
        }

        $tender = Tender::with('constructionSite')->findOrFail($tenderId);
        $items = Item::with('measurementUnit')->get();

        return view('Construction-Module.boq.create', compact('tender', 'items'));
    }

    public function store(StoreBOQMasterRequest $request)
    {
        DB::beginTransaction();
        try {
            $boqMaster = $this->service->create($request->validated());

            DB::commit();

            return redirect()->route('boq-masters.show', $boqMaster->id)
                ->with('success', __('messages.boq-created-successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('BOQ Master Creation Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', __('messages.an-error-occurred'));
        }
    }

    public function show($id)
    {
        $boqMaster = BOQMaster::with(['constructionSite', 'tender', 'details.item'])->findOrFail($id);
        return view('Construction-Module.boq.show', compact('boqMaster'));
    }

    public function edit($id)
    {
        $boqMaster = BOQMaster::with(['constructionSite', 'tender', 'details'])->findOrFail($id);
        $items = Item::all();

        return view('Construction-Module.boq.edit', compact('boqMaster', 'items'));
    }

    public function update(UpdateBOQMasterRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $this->service->update($request->validated(), $id);

            DB::commit();

            $boqMaster = BOQMaster::findOrFail($id);
            return redirect()->route('boq-masters.show', $boqMaster->id)
                ->with('success', __('messages.boq-updated-successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('BOQ Master Update Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'boq_id' => $id,
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', __('messages.an-error-occurred'));
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $boqMaster = BOQMaster::findOrFail($id);
            $constructionSiteId = $boqMaster->construction_site_id;

            $this->service->delete($id);

            DB::commit();

            return redirect()->route('boq-masters.index', ['constructionSiteId' => $constructionSiteId])
                ->with('success', __('messages.boq-deleted-successfully'));
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('BOQ Master Deletion Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'boq_id' => $id,
            ]);

            return redirect()->back()
                ->with('error', __('messages.an-error-occurred'));
        }
    }

    /**
     * Get BOQs by Tender (AJAX endpoint)
     */
    public function getByTender($tenderId)
    {
        $boqs = BOQMaster::where('tender_id', $tenderId)
            ->with(['details'])
            ->get()
            ->map(function ($boq) {
                return [
                    'id' => $boq->id,
                    'title_en' => $boq->title_en,
                    'title_ur' => $boq->title_ur,
                    'total_amount' => $boq->total_amount,
                    'items_count' => $boq->details->count(),
                ];
            });

        return response()->json($boqs);
    }

    /**
     * Get Item Measurement Unit (AJAX endpoint)
     */
    public function getItemMeasurementUnitDetail($itemId)
    {
        try {
            $item = Item::with('measurementUnit')->findOrFail($itemId);
            $field = app()->getLocale() === 'ur' ? 'name_ur' : 'name_en';
            $unit = $item->measurementUnit;

            return response()->json([
                'success' => true,
                'data' => $unit ? $unit->{$field} : '-'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'data' => '-'
            ]);
        }
    }

}
