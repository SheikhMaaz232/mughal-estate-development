<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreRelationRequest;
use App\Models\Relation;
use App\Services\RelationService;

class RelationController extends Controller
{
    protected $relationService;

    public function __construct(RelationService $relationService)
    {
        $this->relationService = $relationService;
    }

    /**
     * Display a paginated list of all relations.
     */
    public function index()
    {
        try {
            $relationsData = Relation::latest()->paginate(10);
            return view('registration.relations.index', compact('relationsData'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form to create a new relation.
     */
    public function create()
    {
        try {
            return view('registration.relations.create');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Store a newly created relation in storage.
     */
    public function store(StoreRelationRequest $request)
    {
        try {
            Relation::create($request->validated());

            return redirect()->route('relations.index')
                ->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form to edit an existing relation.
     */
    public function edit($id)
    {
        try {
            $relation = Relation::findOrFail($id);
            return view('registration.relations.edit', compact('relation'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified relation.
     */
    public function update(StoreRelationRequest $request, $id)
    {
        try {
            $this->relationService->update($id, $request->validated());

            return redirect()->route('relations.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Delete the specified relation.
     */
    public function destroy($id)
    {
        try {
            $this->relationService->delete($id);

            return redirect()->route('relations.index')
                ->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }
}
