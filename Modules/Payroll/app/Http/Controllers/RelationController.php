<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreRelationRequest;
use App\Models\Relation;
use Modules\Payroll\Http\Requests\UpdateRelationRequest;

class RelationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $relations = Relation::latest()->paginate(10);
        return view('payroll::registration.relations.index', compact('relations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.relations.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreRelationRequest $request)
        {
            Relation::create($request->all());

            return redirect()->route('payroll.relations')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Relation $relation)
    {
        return view('payroll::registration.relations.edit', compact('relation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRelationRequest $request, Relation $relation)
    {
        $relation->update($request->all());

        return redirect()->route('payroll.relations')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Relation $relation)
    {
        $relation->delete();

        return redirect()->route('payroll.relations')
            ->with('success', __('messages.record-deleted'));
    }

}
