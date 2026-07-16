<?php

namespace App\Http\Controllers\Registration;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Services\PartiesService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StorePartiesRequest;

class RegisteredPartiesController extends Controller
{
    protected $partiesService;

    public function __construct(PartiesService $partiesService)
    {
        $this->partiesService = $partiesService;
    }

    /**
     * Display a listing of Sub-Sub-Heads.
     */
    public function index(Request $request)
    {

        $search = $request->input('search');
        $request = $request->all();

        $parties = Party::with('cast', 'residentialStatus', 'occupation')->search($search, $request)->latest()->paginate(10);

        return view('registration.party_registration.index', compact('parties', 'search'));
    }

    /**
     * Show the form for creating a new Sub-Sub-Head.
     */
    public function create()
    {
        return view('registration.party_registration.create');
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

            return view('registration.party_registration.edit', compact('registeredParty', 'partyBanks'));
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
