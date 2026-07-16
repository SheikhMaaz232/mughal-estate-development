<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;
use App\Models\JournalVoucher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\JournalVoucherService;
use App\Models\JournalEntry;

class JournalVoucherController extends Controller
{
    protected $journalVoucherService;

    public function __construct(JournalVoucherService $journalVoucherService)
    {
        $this->journalVoucherService = $journalVoucherService;
    }

    /**
     * Display a listing of journalVouchers.
     */
    public function index(Request $request)
    {

        $filters = $request->all();

        $journalVouchers = JournalVoucher::search($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('registration.vouchers.jv.index', compact('journalVouchers'));
    }

    /**
     * Show the form for creating a new journalVoucher.
     */
    public function create()
    {
        $maxid = JournalVoucher::withTrashed()->max('id') + 1;
        return view('registration.vouchers.jv.create', compact('maxid'));
    }

    /**
     * Store a newly created journalVoucher in storage.
     */
    public function store(Request $request)
    {
        // try {
        //     DB::beginTransaction();
            // Prepare data
            $preparedData = $this->journalVoucherService->prepare($request->all());
            // Store data
            $this->journalVoucherService->store($preparedData);

            // DB::commit();
            return redirect()->route('jv-voucher.index')
                ->with('success', __('messages.jv_saved_success'));
        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->withErrors(['error' => $e->getMessage()]);
        // }
    }

    /**
     * Show the form for editing the specified journalVoucher.
     */
    public function edit($id)
    {
        try {
            $journalVoucher = $this->journalVoucherService->getById($id);
            $journalVoucherDetails = JournalEntry::where('journal_voucher_id', $journalVoucher->id)->get();

            return view('registration.vouchers.jv.edit', compact('journalVoucher', 'journalVoucherDetails'));
        } catch (\Exception $e) {
            return redirect()->route('jv-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified journalVoucher in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $preparedData = $this->journalVoucherService->prepare($request->all());

            $this->journalVoucherService->update($id, $preparedData);

            DB::commit();

            return redirect()->route('jv-voucher.index')
                ->with('success', __('messages.jv_updated_success'));
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified journalVoucher details with related journalVoucher.
     *
     * @param  \App\Models\journalVoucher  $journalVoucher
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        // try {
        $journalVoucher = $this->journalVoucherService->getById($id);
        $journalVoucherDetails = JournalEntry::where('journal_voucher_id', $journalVoucher->id)->get();
        return view('registration.vouchers.jv.show', compact('journalVoucher', 'journalVoucherDetails'));
        // } catch (\Exception $e) {
        //     // Redirect back with error message
        //     return redirect()->back()->with('error', __('messages.unexpected-error'));
        // }
    }

    /**
     * Remove the specified journalVoucher from storage.
     */

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->journalVoucherService->delete($id);

            DB::commit();

            return redirect()->route('jv-voucher.index')
                ->with('success', __('messages.jv_deleted_success'));
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
