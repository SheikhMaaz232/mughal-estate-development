<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;
use App\Models\PossessionLetter;
use App\Models\BookingApplication;
use App\Http\Controllers\Controller;
use App\Services\PossessionLetterService;
use App\Http\Requests\Registration\PossessionLetterRequest;

class PossessionLetterController extends Controller
{
    protected $possessionLetterService;

    public function __construct(PossessionLetterService $possessionLetterService)
    {
        $this->possessionLetterService = $possessionLetterService;
    }

    /**
     * Display a listing of Possession Letters.
     */
    public function index(Request $request)
    {
        $search = $request->all();
        $possessionLettersListing = PossessionLetter::with('project', 'product', 'party')->latest()->paginate(10);

        return view('registration.possession-letter.index', compact('possessionLettersListing', 'request'));
    }

    public function bookingListing(Request $request)
    {
        $search = $request->all();
        $bookings = BookingApplication::where('status', 'Verified')->search($search)->latest()->paginate(10);

        return view('registration.possession-letter.verifiedBookings', compact('bookings'));
    }

    /**
     * Show the form for creating a new Possession Letters.
     */
    public function create(Request $request)
    {
        $booking = BookingApplication::with('party', 'project', 'product')->findOrFail($request->id);

        return view('registration.possession-letter.create', compact('booking'));
    }

    /**
     * Store a newly created Possession Letters in storage.
     */
    public function store(PossessionLetterRequest $request)
    {

        try {
            $data = $request->all();

            app(PossessionLetterService::class)->create($data);

            return redirect()->route('possession-letter.index')->with('success', __('messages.record-saved'));
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
            $possessionLetter = $this->possessionLetterService->getById($id);

            return view('registration.possession-letter.edit', compact('possessionLetter'));
        } catch (\Exception $e) {
            return redirect()->route('possession-letter.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified Sub-Sub-Head in storage.
     */
    public function update(PossessionLetterRequest $request, $id)
    {
        try {
            $data = $request->all();
            $this->possessionLetterService->update($data, $id);

            return redirect()->route('possession-letter.index')->with('success', __('messages.record-updated'));
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
    public function show(PossessionLetter $possessionLetter)
    {
        try {
            return view('registration.possession-letter.show', compact('possessionLetter'));
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
            $this->possessionLetterService->delete($id);
            return redirect()->route('possession-letter.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('possession-letter.index')->with('error', __('messages.unexpected-error'));
        }
    }
}
