<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;

use App\Http\Requests\Registration\MainHeadRequest;
use App\Http\Requests\Registration\UpdateMainHeadRequest;
use App\Models\MainHead;
use App\Services\MainHeadService;
use Illuminate\Http\Request;

class MainHeadController extends Controller
{
    protected $mainHeadService;

    public function __construct(MainHeadService $mainHeadService)
    {
        $this->mainHeadService = $mainHeadService;
    }

    public function index()
    {
        $mainHeads = $this->mainHeadService->getAll();
        return view('registration.main_heads.index', compact('mainHeads'));
    }

    public function create()
    {
        return view('registration.main_heads.create');
    }

    public function store(MainHeadRequest $request)
    {
        $this->mainHeadService->create($request->only('name_en','name_ur'));
        return redirect()->route('main-heads.index')->with('success', __('messages.record-saved'));
    }
    public function edit(MainHead $mainHead)
    {
        return view('registration.main_heads.edit', compact('mainHead'));
    }

    public function update(UpdateMainHeadRequest $request, $id)
    {
        $this->mainHeadService->update($id, $request->only('name_ur', 'name_en'));
        return redirect()->route('main-heads.index')->with('success', __('messages.record-updated'));
    }

    public function destroy($id)
    {
        $this->mainHeadService->delete($id);
        return redirect()->route('main-heads.index')->with('success', __('messages.record-deleted'));
    }
}
