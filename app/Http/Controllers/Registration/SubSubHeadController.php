<?php

namespace App\Http\Controllers\Registration;

use App\Models\ControlHead;
use App\Services\SubSubHeadService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\SubSubHeadRequest;
use App\Models\MainHead;
use App\Models\SubHead;
use App\Models\SubSubHead;
use Illuminate\Http\Request;

class SubSubHeadController extends Controller
{
    protected $subSubHeadService;

    public function __construct(SubSubHeadService $subSubHeadService)
    {
        $this->subSubHeadService = $subSubHeadService;
    }

    /**
     * Display a listing of Sub-Sub-Heads.
     */
    public function index(Request $request)
    {

        $search = $request->input('search');
        $request = $request->all();

        $subSubHeads = SubSubHead::with('mainHead', 'controlHead', 'subHead')->search($search, $request)->latest()->paginate(10)->appends(request()->input());

        return view('registration.sub_sub_heads.index', compact('subSubHeads', 'search'));
    }

    /**
     * Show the form for creating a new Sub-Sub-Head.
     */
    public function create()
    {
        return view('registration.sub_sub_heads.create');
    }

    /**
     * Store a newly created Sub-Sub-Head in storage.
     */
    public function store(SubSubHeadRequest $request)
    {
        try {
            $this->subSubHeadService->create($request->only('main_head_id', 'control_head_id', 'sub_head_id', 'name_en', 'name_ur'));
            return redirect()->route('sub-sub-heads.index')->with('success', __('messages.record-saved'));
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
            $subSubHead = $this->subSubHeadService->getById($id);
            $controlHeads = ControlHead::where('main_head_id', $subSubHead->main_head_id)->get();
            $subHeads = SubHead::where('control_head_id', $subSubHead->control_head_id)->get();

            return view('registration.sub_sub_heads.edit', compact('subSubHead', 'controlHeads', 'subHeads'));
        } catch (\Exception $e) {
            return redirect()->route('sub-sub-heads.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified Sub-Sub-Head in storage.
     */
    public function update(SubSubHeadRequest $request, $id)
    {
        try {

            $this->subSubHeadService->update($id, $request->only('main_head_id', 'control_head_id', 'sub_head_id', 'name_en', 'name_ur'));

            return redirect()->route('sub-sub-heads.index')->with('success', __('messages.record-updated'));
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
            $this->subSubHeadService->delete($id);
            return redirect()->route('sub-sub-heads.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('sub-sub-heads.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Return Sub-Sub-Heads list for given Sub-Head (used in dependent dropdown).
     */
    public function getSubSubAccountForSubHead($subHead)
    {
        try {
            $subAccounts = $this->subSubHeadService->getSubSubHeadsForSubHead($subHead);
            if ($subAccounts) {
                return response()->json(['status' => 'success', 'data' => $subAccounts]);
            }
            return response()->json(['status' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => []]);
        }
    }
}
