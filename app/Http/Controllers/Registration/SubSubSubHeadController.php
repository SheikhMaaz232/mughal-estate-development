<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\SubSubSubHeadRequest;
use App\Models\ControlHead;
use App\Models\Project;
use App\Models\SubHead;
use App\Models\SubSubHead;
use App\Models\SubSubSubHead;
use App\Services\SubSubSubHeadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SubSubSubHeadController extends Controller
{
    protected $subSubSubHeadService;

    public function __construct(SubSubSubHeadService $subSubSubHeadService)
    {
        $this->subSubSubHeadService = $subSubSubHeadService;
    }

    /**
     * Display a listing of Sub-Sub-Heads.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $request = $request->all();

        $subSubSubHeads = SubSubSubHead::with('mainHead', 'controlHead', 'subHead', 'subSubHead')->search($search, $request)->latest()->paginate(10)->appends(request()->input());

        return view('registration.sub_sub_sub_heads.index', compact('subSubSubHeads', 'search'));
    }

    /**
     * Show the form for creating a new Sub-Sub-Head.
     */
    public function create(Request $request)
    {
        return view('registration.sub_sub_sub_heads.create');
    }

    /**
     * Store a newly created Sub-Sub-Head in storage.
     */
    public function store(SubSubSubHeadRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->all();

            $subSubSubHeadService = app(SubSubSubHeadService::class);

            $createdItems = [];

            foreach ($request->project_id as $projectId) {

                $itemData = $data;

                // Assign single project id for this record
                $itemData['project_id'] = $projectId;

                // Get project information
                $project = Project::find($projectId);

                if ($project) {

                    // Prefix project name with entered name
                    $itemData['name_en'] = trim(
                        ($project->name_en ?? '') . ' ' . ($request->name_en ?? '')
                    );

                    $itemData['name_ur'] = trim(
                        ($project->name_ur ?? '') . ' ' . ($request->name_ur ?? '')
                    );
                }

                // Create record
                $item = $subSubSubHeadService->create($itemData);

                $createdItems[] = $item;
            }

            DB::commit();

            // $this->subSubSubHeadService->create($request->only('main_head_id', 'control_head_id', 'sub_head_id', 'sub_sub_head_id', 'project_id', 'name_en', 'name_ur'));
            return redirect()->route('sub-sub-sub-heads.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified Sub-Sub-Head.
     */
    public function edit($id)
    {
        try {
            $subSubSubHead = $this->subSubSubHeadService->getById($id);
            $controlHeads = ControlHead::where('main_head_id', $subSubSubHead->main_head_id)->get();
            $subHeads = SubHead::where('control_head_id', $subSubSubHead->control_head_id)->get();
            $subSubHeads = SubSubHead::where('sub_head_id', $subSubSubHead->sub_head_id)->get();

            return view('registration.sub_sub_sub_heads.edit', compact('subSubSubHead', 'controlHeads', 'subHeads', 'subSubHeads'));
        } catch (\Exception $e) {
            return redirect()->route('sub-sub-sub-heads.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified Sub-Sub-Head in storage.
     */
    public function update(SubSubSubHeadRequest $request, $id)
    {
        try {

            $this->subSubSubHeadService->update($id, $request->only('main_head_id', 'control_head_id', 'sub_head_id', 'sub_sub_head_id', 'project_id', 'name_en', 'name_ur'));

            return redirect()->route('sub-sub-sub-heads.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified Sub-Sub-Head from storage.
     */
    public function destroy($id)
    {
        try {
            $this->subSubSubHeadService->delete($id);
            return redirect()->route('sub-sub-sub-heads.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('sub-sub-sub-heads.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Return Sub-Sub-Heads list for given Sub-Head (used in dependent dropdown).
     */
    public function getSubSubSubAccountForSubSubHead($subSubHead)
    {
        try {
            $subSubSubAccounts = $this->subSubSubHeadService->getSubSubSubHeadsForSubSubHead($subSubHead);
            if ($subSubSubAccounts) {
                return response()->json(['status' => 'success', 'data' => $subSubSubAccounts]);
            }
            return response()->json(['status' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => []]);
        }
    }
}
