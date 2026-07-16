<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreCastRequest;
use App\Http\Requests\Registration\UpdateCastRequest;
use App\Models\Cast;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function index()
    {
        $casts = Cast::latest()->paginate(10);
        return view('registration.casts.index', compact('casts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.casts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCastRequest $request)
    {
        Cast::create($request->all());

        return redirect()->route('casts.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cast $cast)
    {
        return view('registration.casts.edit', compact('cast'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateCastRequest $request, Cast $cast)
    {
        $cast->update($request->all());

        return redirect()->route('casts.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cast $cast)
    {
        $cast->delete();

        return redirect()->route('casts.index')
            ->with('success', __('messages.record-deleted'));
    }
}
