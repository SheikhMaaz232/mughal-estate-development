<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;

use App\Http\Requests\Registration\StoreCityRequest;
use App\Http\Requests\Registration\UpdateCityRequest;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $cities = City::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name_en', 'like', '%' . $search . '%')
                    ->orWhere('name_ur', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(5)
        ->appends(['search' => $search]); // This preserves search in pagination links

        return view('registration.cities.index', compact('cities'));
    }
    
    public function create()
    {
        return view('registration.cities.create');
    }

    public function store(StoreCityRequest $request)
    {
        City::create($request->all());

        return redirect()->route('cities.index')
            ->with('success', __('messages.record-saved'));
    }

    public function edit(City $city)
    {
        return view('registration.cities.edit', compact('city'));
    }

    public function update(UpdateCityRequest $request, City $city)
    {
        $city->update($request->all());

        return redirect()->route('cities.index')
            ->with('success', 'City updated successfully');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('cities.index')
            ->with('success', 'City deleted successfully');
    }
}
