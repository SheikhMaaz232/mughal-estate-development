<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\CashPaymentVoucher;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\CashPaymentVoucherService;
use App\Http\Requests\Registration\CashPaymentVoucherRequest;

class CashPaymentVoucherController extends Controller
{
    protected $cashPaymentVoucherService;

    public function __construct(CashPaymentVoucherService $cashPaymentVoucherService)
    {
        $this->cashPaymentVoucherService = $cashPaymentVoucherService;
    }

    /**
     * Display a listing of cashPaymentVouchers.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $cashPaymentVouchers = CashPaymentVoucher::with('project', 'detailAccount', 'cash')->search($filters)->latest()->paginate(10);

        return view('registration.vouchers.cpv.index', compact('cashPaymentVouchers'));
    }

    /**
     * Show the form for creating a new cashPaymentVoucher.
     */
    public function create()
    {
        $maxid = CashPaymentVoucher::max('id') + 1;
        return view('registration.vouchers.cpv.create', compact('maxid'));
    }

    /**
     * Store a newly created cashPaymentVoucher in storage.
     */
    public function store(CashPaymentVoucherRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            $cPVoucherData = app(cashPaymentVoucherService::class)->create($data);
            $this->cashPaymentVoucherService->prepareAccountDebitData($request, $cPVoucherData->id);
            $this->cashPaymentVoucherService->prepareAccountCreditData($request, $cPVoucherData->id);

            DB::commit();
            return redirect()->route('cash-payment-voucher.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified cashPaymentVoucher.
     */
    public function edit($id)
    {
        try {
            $cashPaymentVoucher = $this->cashPaymentVoucherService->getById($id);

            return view('registration.vouchers.cpv.edit', compact('cashPaymentVoucher'));
        } catch (\Exception $e) {
            return redirect()->route('cash-payment-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified cashPaymentVoucher in storage.
     */
    public function update(CashPaymentVoucherRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();
            $cPVoucherUpdatedData = $this->cashPaymentVoucherService->update($id, $data, $request->file('attachment'));

            $documentNo = 'CPV' . '-' . $id;
            AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();

            $this->cashPaymentVoucherService->prepareAccountDebitData($request, $cPVoucherUpdatedData->id);
            $this->cashPaymentVoucherService->prepareAccountCreditData($request, $cPVoucherUpdatedData->id);

            DB::commit();
            return redirect()->route('cash-payment-voucher.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified cashPaymentVoucher details with related cash Accounts.
     *
     * @param  \App\Models\cashPaymentVoucher  $cashPaymentVoucher
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(CashPaymentVoucher $cashPaymentVoucher)
    {
        try {
            // Return the Blade view with cashPaymentVoucher details
            return view('registration.vouchers.cpv.show', compact('cashPaymentVoucher'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified cashPaymentVoucher from storage.
     */
    public function destroy($id)
    {
        try {
            $this->cashPaymentVoucherService->delete($id);
            return redirect()->route('cash-payment-voucher.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('cash-payment-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Get Control Heads based on Main Head for dependent dropdown
     */
    public function getCashAccountsAndDetailAccount($projectId)
    {
        try {
            $cashDetailAccounts = $this->cashPaymentVoucherService->getCashAccountsAndDetailAccount($projectId);

            if (!empty($cashDetailAccounts['payables']) || !empty($cashDetailAccounts['cashAccounts'])) {
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
