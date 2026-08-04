<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StorePartiesRequest;
use App\Models\Bank;
use App\Models\Cast;
use App\Models\OccupationType;
use App\Models\Party;
use App\Models\Residential;
use App\Services\PartiesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RegisteredPartiesController extends Controller
{
    protected $partiesService;

    public function __construct(PartiesService $partiesService)
    {
        $this->partiesService = $partiesService;
    }

    private function getMasterData()
    {
        return [
            'casts' => Cache::remember('casts_data', 3600, fn() =>
            Cast::select('id', 'title_en', 'title_ur')->get()),

            'occupations' => Cache::remember('occupations_data', 3600, fn() =>
            OccupationType::select('id', 'title_en', 'title_ur')->get()),

            'residentialStatus' => Cache::remember('residential_status_data', 3600, fn() =>
            Residential::select('id', 'title_en', 'title_ur')->get()),

            'banks' => Cache::remember('banks_data', 3600, fn() =>
            Bank::select('id', 'name_en', 'name_ur')->get()),
        ];
    }

    /**
     * Display a listing of Sub-Sub-Heads.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = $request->all();
        $page = $request->get('page', 1);

        $cacheKey = 'parties_' . md5(json_encode([
            'search' => $search,
            'filters' => $filters,
            'page' => $page,
        ]));

        $parties = Cache::remember($cacheKey, 300, function () use ($search, $filters) {
            return Party::with(['cast', 'residentialStatus', 'occupation'])
                ->search($search, $filters)
                ->latest()
                ->paginate(10);
        });
        return view(
            'registration.party_registration.index',
            array_merge(
                [
                    'parties' => $parties,
                    'search' => $search,
                ],
                $this->getMasterData()
            )
        );
    }

    /**
     * Show the form for creating a new Sub-Sub-Head.
     */
    public function create()
    {
        return view('registration.party_registration.create',
            $this->getMasterData()
        );
    }

    /**
     * Store a newly created Sub-Sub-Head in storage.
     */
    public function store(StorePartiesRequest $request)
    {
        try {
            $data = $request->all();
            app(PartiesService::class)->create($data);

            return redirect()->route('parties.index')->with('success', __('messages.record-saved'));
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
            $registeredParty = $this->partiesService->getById($id);
            $partyBanks = $this->partiesService->getPartyBanksByPartyId($id);

            return view(
                'registration.party_registration.edit',
                array_merge(
                    [
                        'registeredParty' => $registeredParty,
                        'partyBanks' => $partyBanks,
                    ],
                    $this->getMasterData()
                )
            );
        } catch (\Exception $e) {
            return redirect()->route('parties.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified Sub-Sub-Head in storage.
     */
    public function update(StorePartiesRequest $request, $id)
    {
        try {
            $data = $request->all();
            $this->partiesService->update($id, $data, $request->file('cnic_front_image'), $request->file('cnic_back_image'), $request->file('profile_image'));

            return redirect()->route('parties.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified Party details with related Bank Accounts.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Party $party)
    {
        try {
            // Fetch related bank details of this Party using Service layer
            $partyBanks = $this->partiesService->getPartyBanksByPartyId($party->id);

            // Return the Blade view with Party and Bank details
            return view('registration.party_registration.show', compact('party', 'partyBanks'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified Sub-Sub-Head from storage.
     */
    public function destroy($id)
    {
        try {
            $this->partiesService->delete($id);
            return redirect()->route('parties.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('parties.index')->with('error', __('messages.unexpected-error'));
        }
    }
}
