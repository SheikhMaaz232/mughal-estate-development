<?php

namespace App\Http\Controllers\Registration;

use App\Models\ControlHead;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ControlHeadService;
use App\Http\Requests\Registration\ControlHeadRequest;

class ControlHeadController extends Controller
{
    protected $controlHeadService;

    /**
     * Constructor to inject ControlHeadService
     */
    public function __construct(ControlHeadService $controlHeadService)
    {
        $this->controlHeadService = $controlHeadService;
    }

    /**
     * Display list of Control Heads
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $request = $request->all();

        $controlHeads = ControlHead::with('mainHead')->search($search, $request)->latest()->paginate(10);

        return view('registration.control_heads.index', compact('controlHeads', 'search'));
    }

    /**
     * Show form to create a new Control Head
     */
    public function create()
    {
        return view('registration.control_heads.create');
    }

    /**
     * Store new Control Head in database
     */
    public function store(ControlHeadRequest $request)
    {
        try {
            $this->controlHeadService->create($request->only('main_head_id', 'name_en', 'name_ur'));
            return redirect()->route('control-heads.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show form to edit an existing Control Head
     */
    public function edit($id)
    {
        $controlHeads = $this->controlHeadService->getById($id);
        return view('registration.control_heads.edit', compact('controlHeads'));
    }

    /**
     * Update existing Control Head
     */
    public function update(ControlHeadRequest $request, $id)
    {
        try {
            $this->controlHeadService->update($id, $request->only('main_head_id', 'name_en', 'name_ur'));
            return redirect()->route('control-heads.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Delete a Control Head by ID
     */
    public function destroy($id)
    {
        try {
            $this->controlHeadService->delete($id);
            return redirect()->route('control-heads.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Get Control Heads based on Main Head for dependent dropdown
     */
    public function getControlAccountForMainHead($mainHead)
    {
        $controlAccounts = $this->controlHeadService->getControlHeadsForMainHead($mainHead);

        if ($controlAccounts) {
            return response()->json(['status' => 'success', 'data' => $controlAccounts]);
        }
        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
