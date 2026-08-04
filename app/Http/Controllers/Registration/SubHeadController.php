<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\SubHeadRequest;
use App\Models\ControlHead;
use App\Models\MainHead;
use App\Models\SubHead;
use App\Services\SubHeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubHeadController extends Controller
{
    protected $subHeadService;

    /**
     * Inject SubHeadService
     */
    public function __construct(SubHeadService $subHeadService)
    {
        $this->subHeadService = $subHeadService;
    }

    /**
     * List all Sub Heads
     */
    public function index(Request $request)
    {

        $search = $request->input('search');
        $request = $request->all();

        $mainHeads = Cache::remember('main_heads_data', 3600, function () {
            return MainHead::select('id', 'name_en', 'name_ur')->get();
        });
        $searchControlHeads = Cache::remember('control_heads_data', 3600, function () {
            return ControlHead::select('id', 'name_en', 'name_ur')->get();
        });

        $subHeads = SubHead::with('mainHead', 'controlHead')->search($search, $request)->latest()->paginate(10)->appends(request()->input());

        return view('registration.sub_heads.index', compact('subHeads', 'search', 'mainHeads', 'searchControlHeads'));
    }

    /**
     * Show form to create a Sub Head
     */
    public function create()
    {
        $mainHeads = Cache::remember('main_heads_data', 3600, function () {
            return MainHead::select('id', 'name_en', 'name_ur')->get();
        });
        $controlHeads = Cache::remember('control_heads_data', 3600, function () {
            return ControlHead::select('id', 'name_en', 'name_ur')->get();
        });
        return view('registration.sub_heads.create', compact('mainHeads', 'controlHeads'));
    }

    /**
     * Store new Sub Head
     */
    public function store(SubHeadRequest $request)
    {
        try {
            $this->subHeadService->create($request->only('main_head_id', 'control_head_id', 'name_en', 'name_ur'));
            return redirect()->route('sub-heads.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show form to edit a Sub Head
     */
    public function edit($id)
    {
        $subHeads = $this->subHeadService->getById($id);
        $mainHeads = Cache::remember('main_heads_data', 3600, function () {
            return MainHead::select('id', 'name_en', 'name_ur')->get();
        });
        $controlHeads = Cache::remember('control_heads_data', 3600, function () {
            return ControlHead::select('id', 'name_en', 'name_ur')->get();
        });
        return view('registration.sub_heads.edit', compact('subHeads', 'mainHeads', 'controlHeads'));
    }

    /**
     * Update Sub Head
     */
    public function update(SubHeadRequest $request, $id)
    {
        try {
            $this->subHeadService->update($id, $request->only('main_head_id', 'control_head_id', 'name_en', 'name_ur'));
            return redirect()->route('sub-heads.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Delete a Sub Head
     */
    public function destroy($id)
    {
        try {
            $this->subHeadService->delete($id);
            return redirect()->route('sub-heads.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {

            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Fetch Sub Heads for given Control Head (for dependent dropdown)
     */
    public function getSubAccountForControlHead($subHead)
    {
        $subAccounts = $this->subHeadService->getSubHeadsForControlHead($subHead);

        if ($subAccounts) {
            return response()->json(['status' => 'success', 'data' => $subAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
