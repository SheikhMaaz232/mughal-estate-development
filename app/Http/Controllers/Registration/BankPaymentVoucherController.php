<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\BankPaymentVoucherRequest;
use App\Models\AccountLedger;
use App\Models\BankPaymentVoucher;
use App\Models\BookingApplication;
use App\Models\ContractorBillPayment;
use App\Services\BankPaymentVoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankPaymentVoucherController extends Controller
{
    protected $bankPaymentVoucherService;

    public function __construct(BankPaymentVoucherService $bankPaymentVoucherService)
    {
        $this->bankPaymentVoucherService = $bankPaymentVoucherService;
    }

    /**
     * Display a listing of bankPaymentVouchers.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $bankPaymentVouchers = BankPaymentVoucher::with('project', 'detailAccount', 'bank')->search($filters)->latest()->paginate(10);

        return view('registration.vouchers.bpv.index', compact('bankPaymentVouchers'));
    }

    /**
     * Show the form for creating a new bankPaymentVoucher.
     */
    public function create()
    {
        $maxid = BankPaymentVoucher::max('id') + 1;
        return view('registration.vouchers.bpv.create', compact('maxid'));
    }

    /**
     * Store a newly created bankPaymentVoucher in storage.
     */
    public function store(BankPaymentVoucherRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $bPVoucherData = app(BankPaymentVoucherService::class)->create($data);
            $this->bankPaymentVoucherService->prepareAccountDebitData($request, $bPVoucherData->id);
            $this->bankPaymentVoucherService->prepareAccountCreditData($request, $bPVoucherData->id);

            if (session()->has('payment_intent')) {

                $paymentIntent = session('payment_intent');

                ContractorBillPayment::create([
                    'contractor_bill_id' => $paymentIntent['bill_id'],
                    'voucher_id'         => $bPVoucherData->id,
                    'voucher_type'       => 'BPV',
                    'amount'             => $paymentIntent['amount'],
                    'payment_date'       => now(),
                    'payment_method'     => 'bank',
                    'status'             => 'posted',
                    'created_by'         => Auth::id(),
                ]);

                session()->forget('payment_intent');
            }

            DB::commit();
            return redirect()->route('bank-payment-voucher.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified bankPaymentVoucher.
     */
    public function edit($id)
    {
        try {
            $bankPaymentVoucher = $this->bankPaymentVoucherService->getById($id);

            return view('registration.vouchers.bpv.edit', compact('bankPaymentVoucher'));
        } catch (\Exception $e) {
            return redirect()->route('bank-payment-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified bankPaymentVoucher in storage.
     */
    public function update(BankPaymentVoucherRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();
            $bPVoucherUpdatedData = $this->bankPaymentVoucherService->update($id, $data, $request->file('attachment'));

            $documentNo = 'BPV' . '-' . $id;
            AccountLedger::where('document_number', $documentNo)->where('invoice_id', $id)->delete();

            $this->bankPaymentVoucherService->prepareAccountDebitData($request, $bPVoucherUpdatedData->id);
            $this->bankPaymentVoucherService->prepareAccountCreditData($request, $bPVoucherUpdatedData->id);

            DB::commit();
            return redirect()->route('bank-payment-voucher.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified bankPaymentVoucher details with related Bank Accounts.
     *
     * @param  \App\Models\BankPaymentVoucher  $bankPaymentVoucher
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(BankPaymentVoucher $bankPaymentVoucher)
    {
        try {
            // Return the Blade view with bankPaymentVoucher details
            return view('registration.vouchers.bpv.show', compact('bankPaymentVoucher'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified bankPaymentVoucher from storage.
     */
    public function destroy($id)
    {
        try {
            $this->bankPaymentVoucherService->delete($id);
            return redirect()->route('bank-payment-voucher.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('bank-payment-voucher.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Get Control Heads based on Main Head for dependent dropdown
     */
    public function getBankAndDetailAccount($projectId)
    {
        try {
            $bankDetailAccounts = $this->bankPaymentVoucherService->getBankAndDetailAccount($projectId);

            if (!empty($bankDetailAccounts['payables']) || !empty($bankDetailAccounts['banks'])) {
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
        $voucher = BankPaymentVoucher::with([
            'detailAccount',
            'project',
            'bank', // if you have detail rows
        ])->findOrFail($id);

        $bookingData = BookingApplication::where('detail_account_id', $voucher->detail_account_id)->with('product', 'project')->first();


        return view('registration.vouchers.bpv.print', compact('voucher', 'bookingData'));
    }
}
