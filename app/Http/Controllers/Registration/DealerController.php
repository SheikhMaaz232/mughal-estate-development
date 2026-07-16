<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreDealerRequest;
use App\Http\Requests\Registration\updateDealerRequest;
use App\Models\Dealer;
use App\Services\CommonService;
use App\Services\DealerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DealerController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $dealers = Dealer::latest()
            ->when($search, function($query, $search) {
                return $query->search($search); // Using the scope
            })
            ->latest()
            ->paginate(10);

        return view('registration.dealers.index', compact('dealers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.dealers.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreDealerRequest $request)
    {
        $data = $request->all();
        app(DealerService::class)->create($data, $request->file('photo'));

        return redirect()->route('dealers.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dealer $dealer)
    {
        return view('registration.dealers.edit', compact('dealer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateDealerRequest $request, Dealer $dealer)
    {
        $dealer->update($request->all());

        $data = $request->validated();

        // $project->update($request->validated());
        if ($dealer->photo && Storage::exists($dealer->photo)) {
            Storage::delete($dealer->photo);
        }

        // Upload new image if present
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->commonService->uploadImage($request->file('photo'), 'dealers');
        }
        $dealer->update($data);

        return redirect()->route('dealers.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dealer $dealer)
    {
        $dealer->delete();

        return redirect()->route('dealers.index')
            ->with('success', __('messages.record-deleted'));
    }
}
