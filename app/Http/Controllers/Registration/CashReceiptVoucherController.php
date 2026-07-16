<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\CashReceiptVoucher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CashReceiptVoucherService;
use App\Http\Requests\Registration\CashReceiptVoucherRequest;

class CashReceiptVoucherController extends Controller
{
    protected $cashReceiptVoucherService;

    public function __construct(CashReceiptVoucherService $cashReceiptVoucherService)
    {
        $this->cashReceiptVoucherService = $cashReceiptVoucherService;
    }

    /**
     * Display a listing of cashReceiptVouchers.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $cashReceiptVouchers = CashReceiptVoucher::with('project', 'detailAccount', 'cash')->search($filters)->latest()->paginate(10);

        return view('registration.vouchers.crv.index', compact('cashReceiptVouchers'));
    }

    /**
     * Show the form for creating a new cashReceiptVoucher.
     */
    public function create()
    {
        $maxid = CashReceiptVoucher::max('id') + 1;
        return view('registration.vouchers.crv.create', compact('maxid'));
    }

    /**
     * Store a newly created cashReceiptVoucher in storage.
     */
    public function store(CashReceiptVoucherRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $cRVoucherData = app(cashReceiptVoucherService::class)->create($data);
            $this->cashReceiptVoucherService->prepareAccountDebitData($request, $cRVoucherData->id);
            $this->cashReceiptVoucherService->prepareAccountCreditData($request, $cRVoucherData->id);

            DB::commit();
            return redirect()->route('cash-receipt-voucher.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified cashReceiptVoucher.
     */
    public function edit($id)
    {
        try {
            $cashReceiptVoucher = $this->cashReceiptVoucherService->getById($id);

            return view('registration.vouchers.crv.edit', compact('cashReceiptVoucher'));
        } catch (\Exception $e) {
            return redirect()->route('cash-receipt-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified cashReceiptVoucher in storage.
     */
    public function update(CashReceiptVoucherRequest $request, $id)
    {

        try {
            DB::beginTransaction();

            $data = $request->all();
            $cRVoucherUpdatedData = $this->cashReceiptVoucherService->update($id, $data, $request->file('attachment'));

            $documentNo = 'CRV' . '-' . $id;
            AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();

            $this->cashReceiptVoucherService->prepareAccountDebitData($request, $cRVoucherUpdatedData->id);
            $this->cashReceiptVoucherService->prepareAccountCreditData($request, $cRVoucherUpdatedData->id);

            DB::commit();
            return redirect()->route('cash-receipt-voucher.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified cashReceiptVoucher details with related cash Accounts.
     *
     * @param  \App\Models\cashReceiptVoucher  $cashReceiptVoucher
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(CashReceiptVoucher $cashReceiptVoucher)
    {
        try {
            // Return the Blade view with cashReceiptVoucher details
            return view('registration.vouchers.crv.show', compact('cashReceiptVoucher'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified cashReceiptVoucher from storage.
     */
    public function destroy($id)
    {
        try {
            $this->cashReceiptVoucherService->delete($id);
            return redirect()->route('cash-receipt-voucher.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('cash-receipt-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Get Control Heads based on Main Head for dependent dropdown
     */
    public function getCashAccountsAndDetailAccount($projectId)
    {
        try {
            $cashDetailAccounts = $this->cashReceiptVoucherService->getCashAccountsAndDetailAccount($projectId);

            if (!empty($cashDetailAccounts['receivables']) || !empty($cashDetailAccounts['cashAccounts'])) {
                return response()->json([
                    'status'  => 'success',
                    'message' => __('messages.data_fetched_successfully'),
                    'data'    => $cashDetailAccounts,
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
}
