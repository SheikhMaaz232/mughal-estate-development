<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\BankReceiptVoucher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\BankReceiptVoucherService;
use App\Http\Requests\Registration\BankReceiptVoucherRequest;
use App\Models\BookingApplication;

class BankReceiptVoucherController extends Controller
{
    protected $bankReceiptVoucherService;

    public function __construct(BankReceiptVoucherService $bankReceiptVoucherService)
    {
        $this->bankReceiptVoucherService = $bankReceiptVoucherService;
    }

    /**
     * Display a listing of bankReceiptVouchers.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $bankReceiptVouchers = BankReceiptVoucher::with('project', 'detailAccount', 'bank')->search($filters)->latest()->paginate(10);

        return view('registration.vouchers.brv.index', compact('bankReceiptVouchers'));
    }

    /**
     * Show the form for creating a new bankReceiptVoucher.
     */
    public function create()
    {
        $maxid = BankReceiptVoucher::max('id') + 1;
        return view('registration.vouchers.brv.create', compact('maxid'));
    }

    /**
     * Store a newly created bankReceiptVoucher in storage.
     */
    public function store(BankReceiptVoucherRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $bRVoucherData = app(bankReceiptVoucherService::class)->create($data);
            $this->bankReceiptVoucherService->prepareAccountDebitData($request, $bRVoucherData->id);
            $this->bankReceiptVoucherService->prepareAccountCreditData($request, $bRVoucherData->id);

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

            return view('registration.vouchers.brv.edit', compact('bankReceiptVoucher'));
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
