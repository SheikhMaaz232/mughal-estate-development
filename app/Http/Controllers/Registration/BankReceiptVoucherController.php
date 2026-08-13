<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\BankReceiptVoucherRequest;
use App\Models\AccountLedger;
use App\Models\BankReceiptVoucher;
use App\Models\BookingApplication;
use App\Models\DetailAccount;
use App\Models\Project;
use App\Services\BankReceiptVoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BankReceiptVoucherController extends Controller
{
    protected $bankReceiptVoucherService;

    public function __construct(BankReceiptVoucherService $bankReceiptVoucherService)
    {
        $this->bankReceiptVoucherService = $bankReceiptVoucherService;
    }

    private function getMasterData()
    {
        return [
            'coaReceivables' => Cache::remember('coa_receivables_data', 3600, fn() =>
            DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_head_id', 1)->get()),

            'coaBanks' => Cache::remember('coa_banks_data', 3600, fn() =>
            DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 19)->get()),

            'projects' => Cache::remember('projects_data', 3600, fn() =>
            Project::select('id', 'name_en', 'name_ur')->get()),

        ];
    }

    /**
     * Display a listing of bankReceiptVouchers.
     */
    public function approvalList(Request $request)
    {
        $filters = $request->all();

        $bankReceiptVouchers = BankReceiptVoucher::with([
            'project',
            'detailAccount',
            'bank'
        ])
            ->search($filters)
            ->orderByRaw("CASE WHEN status = 'Unverified' THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('registration.vouchers.brv.approvallist', array_merge(
            [
                'bankReceiptVouchers' => $bankReceiptVouchers,
            ],
            $this->getMasterData()
        ));
    }

    public function index(Request $request)
    {
        $filters = $request->all();

        $hasFilters =
            $request->filled('voucher_no') ||
            $request->filled('project_id') ||
            $request->filled('detail_account_id') ||
            $request->filled('bank_id');

        if ($hasFilters) {
            $bankReceiptVouchers = BankReceiptVoucher::where('status', 'verified')->with([
                'project',
                'detailAccount',
                'bank'
            ])
                ->search($filters)
                ->latest()
                ->paginate(10)
                ->withQueryString();
        } else {
            // Empty paginator so nothing is shown initially
            $bankReceiptVouchers = BankReceiptVoucher::whereRaw('1 = 0')
                ->paginate(10);
        }

        return view(
            'registration.vouchers.brv.index',
            array_merge(
                [
                    'bankReceiptVouchers' => $bankReceiptVouchers,
                    'hasFilters' => $hasFilters,
                ],
                $this->getMasterData()
            )
        );
    }

    /**
     * Show the form for creating a new bankReceiptVoucher.
     */
    public function create()
    {
        $maxid = BankReceiptVoucher::max('id') + 1;

        return view('registration.vouchers.brv.create', array_merge(
            [
                'maxid' => $maxid,
            ],
            $this->getMasterData()
        ));
    }

    /**
     * Store a newly created bankReceiptVoucher in storage.
     */
    public function store(BankReceiptVoucherRequest $request)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();

            $bRVoucherData = app(bankReceiptVoucherService::class)->create($data);

            DB::commit();
            return redirect()->route('bank-receipt-voucher.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified bankReceiptVoucher.
     */
    public function edit($id)
    {
        try {
            $bankReceiptVoucher = $this->bankReceiptVoucherService->getById($id);

            return view('registration.vouchers.brv.edit', array_merge(
                [
                    'bankReceiptVoucher' => $bankReceiptVoucher,
                ],
                $this->getMasterData()
            ));
        } catch (\Exception $e) {
            return redirect()->route('bank-receipt-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified bankReceiptVoucher in storage.
     */
    public function update(BankReceiptVoucherRequest $request, $id)
    {

        try {
            DB::beginTransaction();
            $data = $request->all();
            $bRVoucherUpdatedData = $this->bankReceiptVoucherService->update($id, $data, $request->file('attachment'));

            $documentNo = 'BRV' . '-' . $id;
            AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();

            $this->bankReceiptVoucherService->prepareAccountDebitData($request, $bRVoucherUpdatedData->id);
            $this->bankReceiptVoucherService->prepareAccountCreditData($request, $bRVoucherUpdatedData->id);

            DB::commit();
            return redirect()->route('bank-receipt-voucher.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified bankReceiptVoucher details with related Bank Accounts.
     *
     * @param  \App\Models\bankReceiptVoucher  $bankReceiptVoucher
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(BankReceiptVoucher $bankReceiptVoucher)
    {
        return view('registration.vouchers.brv.show', compact('bankReceiptVoucher'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $bankReceiptVoucher = BankReceiptVoucher::findOrFail($id);

            if ($bankReceiptVoucher->status === 'verified') {
                throw new \Exception('Voucher is already verified.');
            }

            $documentNo = 'BRV-' . $bankReceiptVoucher->id;

            $ledgerExists = AccountLedger::where('document_number', $documentNo)
                ->where('invoice_id', $bankReceiptVoucher->id)
                ->exists();

            if ($ledgerExists) {
                throw new \Exception('Ledger entries already exist for this voucher.');
            }

            /*
         * Create AccountLedger entries.
         *
         * These methods should create the debit and credit
         * ledger entries for this BRV.
         */
            $this->bankReceiptVoucherService->prepareAccountDebitData(
                $bankReceiptVoucher,
                $bankReceiptVoucher->id
            );

            $this->bankReceiptVoucherService->prepareAccountCreditData(
                $bankReceiptVoucher,
                $bankReceiptVoucher->id
            );

            // Mark BRV as verified
            $bankReceiptVoucher->update([
                'status' => 'verified',
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', __('messages.voucher-approved'));
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified bankReceiptVoucher from storage.
     */
    public function destroy($id)
    {
        try {
            $this->bankReceiptVoucherService->delete($id);
            return redirect()->route('bank-receipt-voucher.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('bank-receipt-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Get Control Heads based on Main Head for dependent dropdown
     */
    public function getBankAndDetailAccount($projectId)
    {
        try {
            $bankDetailAccounts = $this->bankReceiptVoucherService->getBankAndDetailAccount($projectId);

            if (!empty($bankDetailAccounts['receivables']) || !empty($bankDetailAccounts['banks'])) {
                return response()->json([
                    'status'  => 'success',
                    'message' => __('messages.data_fetched_successfully'),
                    'data'    => $bankDetailAccounts,
                ], 200);
            }

            return response()->json([
                'status'  => 'fail',
                'message' => __('messages.no_records_found'),
                'data'    => [],
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => __('messages.something_went_wrong'),
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function print($id)
    {
        $bankReceiptVoucher = BankReceiptVoucher::with([
            'detailAccount',
            'project',
            'bank', // if you have detail rows
        ])->findOrFail($id);

        $bookingData = BookingApplication::where('detail_account_id', $bankReceiptVoucher->detail_account_id)->with('product', 'project')->first();


        return view('registration.vouchers.brv.print', compact('bankReceiptVoucher', 'bookingData'));
    }
}
