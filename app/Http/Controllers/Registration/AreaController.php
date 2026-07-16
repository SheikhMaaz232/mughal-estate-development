<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreAreaRequest;
use App\Http\Requests\Registration\UpdateAreaRequest;
use App\Models\Area;
use App\Models\Tehsil;
use Illuminate\Support\Facades\Cache;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('accounts.index');

        $areas = Area::latest()->paginate(10);
        return view('registration.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('accounts.create');

        return view('registration.areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreAreaRequest $request)
        {
            Area::create($request->all());

            return redirect()->route('areas.index')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return view('registration.areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAreaRequest $request, Area $area)
    {
        $area->update($request->all());

        return redirect()->route('areas.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();

        return redirect()->route('areas.index')
            ->with('success', __('messages.record-deleted'));
    }

    public function getTehsilsByCity($city_id)
    {
        return Cache::remember("tehsils_for_city_{$city_id}", now()->addDay(), function() use ($city_id) {
            return Tehsil::where('city_id', $city_id)
                        ->orderBy('name_en')
                        ->get(['id', 'name_en']);
        });
    }
}
