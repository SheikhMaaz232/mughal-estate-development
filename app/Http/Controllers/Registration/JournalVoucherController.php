<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\DetailAccount;
use App\Models\JournalEntry;
use App\Models\JournalVoucher;
use App\Models\Project;
use App\Services\JournalVoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JournalVoucherController extends Controller
{
    protected $journalVoucherService;

    public function __construct(JournalVoucherService $journalVoucherService)
    {
        $this->journalVoucherService = $journalVoucherService;
    }

    private function getMasterData()
    {
        return [
            'detailAccounts' => Cache::remember('detail_accounts_data', 3600, fn() =>
            DetailAccount::select('id', 'name_en', 'name_ur')->get()),

            'projects' => Cache::remember('projects_data', 3600, fn() =>
            Project::select('id', 'name_en', 'name_ur')->get()),

        ];
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

        return view('registration.vouchers.jv.index', array_merge(
            [
                'journalVouchers' => $journalVouchers,
            ],
            $this->getMasterData()
        ));
    }

    /**
     * Show the form for creating a new journalVoucher.
     */
    public function create()
    {
        $maxid = JournalVoucher::withTrashed()->max('id') + 1;
        return view('registration.vouchers.jv.create', array_merge(
            [
                'maxid' => $maxid,
            ],
            $this->getMasterData()
        ));
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

            return view('registration.vouchers.jv.edit', array_merge(
                [
                    'journalVoucher' => $journalVoucher,
                    'journalVoucherDetails' => $journalVoucherDetails,
                ],
                $this->getMasterData()
            ));
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
