<?php

namespace App\Http\Controllers\Registration;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\BookingReturn;
use App\Models\BookingApplication;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\BookingReturnApplicationService;
use App\Http\Requests\Registration\BookingReturnRequest;
use App\Models\DetailAccount;
use App\Models\SubSubSubHead;

class BookingReturnController extends Controller
{
    protected $bookingReturnApplicationService;

    public function __construct(BookingReturnApplicationService $bookingReturnApplicationService)
    {
        $this->bookingReturnApplicationService = $bookingReturnApplicationService;
    }

    /**
     * Display a listing of Sub-Sub-Heads.
     */
    public function index(Request $request)
    {
        $request = $request->all();
        $bookingReturns = BookingReturn::with('bookingApplication')->latest()->paginate(10);

        return view('registration.booking-returns.index', compact('bookingReturns'));
    }

    /**
     * Display a listing of Bookings.
     */
    public function bookingListing(Request $request)
    {
        $search = $request->all();
        $bookings = BookingApplication::where('status', 'Verified')->where('case', '!=', 'ownership_changed')->where('case', '!=', 'ownership_changed')->search($search)->latest()->paginate(10);

        return view('registration.booking-returns.verifiedBookings', compact('bookings'));
    }


    public function create(Request $request)
    {
        $data = $request->booking_id;
        $alreadySubmitted = BookingReturn::where('booking_id', $data)->exists();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error',  __('messages.request-already-submitted'));
        }

        $bookingApplication = BookingApplication::where('id', $data)->first();
        $subSubSubHead = SubSubSubHead::where('project_id', $bookingApplication->project_id)->pluck('id');
        $projectDetailAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->get();
        $cashBankAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_head_id', 2)->get();
        $cancellationReceivableAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_sub_head_id', 8)->get();
        $liabilityAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_sub_head_id', 52)->get();
        $incomeAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_sub_head_id', 130)->get();

        return view('registration.booking-returns.create', compact('data', 'incomeAccounts', 'liabilityAccounts', 'bookingApplication', 'projectDetailAccounts', 'cancellationReceivableAccounts', 'cashBankAccounts'));
    }

    /**
     * Store a newly created Sub-Sub-Head in storage.
     */

    public function store(BookingReturnRequest $request)
    {
        try {
            $data = $request->all();
            $this->bookingReturnApplicationService->store($data);

            return redirect()->route('bookingReturns.index')->with('success', __('messages.return-application'));
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Booking return store failed', [
                'booking_id' => $request->booking_id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    public function edit($id)
    {
        try {
            $bookingReturn = $this->bookingReturnApplicationService->getById($id);
            $bookingApplication = BookingApplication::where('id', $bookingReturn->booking_id)->first();
            $subSubSubHead = SubSubSubHead::where('project_id', $bookingApplication->project_id)->pluck('id');
            $projectDetailAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->get();
            $cashBankAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_head_id', 2)->get();
            $cancellationReceivableAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_sub_head_id', 8)->get();
            $liabilityAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_sub_head_id', 52)->get();
            $incomeAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHead)->where('sub_sub_head_id', 130)->get();

            return view('registration.booking-returns.edit', compact('bookingReturn', 'bookingApplication', 'projectDetailAccounts', 'cancellationReceivableAccounts', 'cashBankAccounts', 'liabilityAccounts', 'incomeAccounts'));
        } catch (\Exception $e) {
            return redirect()->route('bookings.bookingListing')->with('error', __('messages.unexpected-error'));
        }
    }

    public function update(BookingReturnRequest $request, $id)
    {
        try {
            $this->bookingReturnApplicationService->update($id, $request->validated());

            return redirect()
                ->route('bookingReturns.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {

            Log::error('Booking return update failed', [
                'booking_return_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.something_went_wrong'));
        }
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $bookingApplication = BookingApplication::findOrFail($request->booking_id);
    //         if ($bookingApplication) {
    //             $bookingApplication->update(['status' => 'Cancelled']);
    //         }
    //         $product = Product::findOrFail($bookingApplication->product_id);
    //         if ($product) {
    //             $product->update(['status' => 'Verified']);
    //         }
    //         $bookingReturns = BookingReturn::findOrFail($id);

    //         $request->validate([
    //             'status' => 'required|in:Verified,Unverified'
    //         ]);

    //         $bookingReturns->status = $request->status;
    //         $bookingReturns->save();

    //         $bookingReturn = BookingReturn::lockForUpdate()->findOrFail($id);
    //         $cancellationsCharges = ($bookingReturn->bookingApplication->total_amount * $bookingReturn->percentage_value) / 100;
    //         $documentNo = 'B-R' . '-' . $bookingReturn->id;
    //         //  Only create ledger entries if they don’t already exist
    //         $ledgerExists = AccountLedger::where('invoice_id', $bookingReturn->id)->where('document_number', $documentNo)->exists();
    //         $journalExists = GeneralJournal::where('invoice_id', $bookingReturn->id)->where('document_number', $documentNo)->exists();

    //         if (!$ledgerExists && !$journalExists) {
    //             $this->bookingReturnApplicationService->createLedgerEntry($bookingReturn, $cancellationsCharges);
    //         }

    //         DB::commit();
    //         return redirect()->route('bookingReturns.index')
    //             ->with('success',  __('messages.record-updated'));
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         Log::error(' Booking verification failed', [
    //             'booking_id' => $id,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return redirect()->route('bookingReturns.index')
    //             ->with('error', 'An error occurred while verifying the booking. Please try again.');
    //     }
    // }

    public function updateStatus(Request $request, $id)
    {
        // try {
        //     DB::beginTransaction();

        $request->validate([
            'status' => 'required|in:Verified,Unverified'
        ]);

        $bookingReturn = BookingReturn::with('bookingApplication')
            ->lockForUpdate()
            ->findOrFail($id);

        $bookingReturn->update([
            'status' => $request->status
        ]);

        // Only process financial effect when Verified
        // if ($request->status === 'Unverified') {

        $bookingApplication = $bookingReturn->bookingApplication;

        // Cancel booking
        $bookingApplication->update(['status' => 'Cancelled']);

        // Make product available
        Product::where('id', $bookingApplication->product_id)
            ->update(['status' => 'Verified']);

        // Prevent duplicate posting
        $documentNo = 'B-R-' . $bookingReturn->id;

        $exists = AccountLedger::where('document_number', $documentNo)
            ->where('invoice_id', $bookingReturn->id)
            ->exists();

        // if (!$exists) {
        $this->bookingReturnApplicationService
            ->createLedgerEntry($bookingReturn);
        // }
        // }

        // DB::commit();

        return redirect()->route('bookingReturns.index')
            ->with('success', __('messages.record-updated'));
        // } catch (\Throwable $e) {

        //     DB::rollBack();

        //     Log::error('Booking return verification failed', [
        //         'id' => $id,
        //         'error' => $e->getMessage()
        //     ]);

        //     return redirect()->route('bookingReturns.index')
        //         ->with('error', 'Financial posting failed.');
        // }
    }



    public function show($id)
    {
        // Load the record with required relationships
        $fileCancellation = BookingReturn::with([
            'bookingApplication',
            'project'
        ])->findOrFail($id);

        $detailAccountId = $fileCancellation->bookingApplication->detail_account_id;

        $bookingValue = $fileCancellation->bookingApplication->total_amount;
        $cancellationsCharges = ($fileCancellation->percentage_value / 100) * $bookingValue;

        // sum of credit from account_ledgers
        $totalCredit = AccountLedger::where('detail_account_id', $detailAccountId)
            ->sum('credit');

        $discountValue = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $fileCancellation->bookingApplication->id)->where('transaction_type', 'feeses_discount')->sum('credit');
        $balanceAmount = $totalCredit - $discountValue;

        $totalDebit = AccountLedger::where('detail_account_id', $detailAccountId)
            ->sum('debit');

        $remainingAmount = $totalDebit - $totalCredit;
        $payableAmount = $bookingValue - $cancellationsCharges;
        $netAmount = $totalCredit - $cancellationsCharges - $discountValue;

        if ($netAmount < 0) {
            // Customer will pay company
            $customerPays = abs($netAmount);
            $companyPays  = 0;
        } elseif ($netAmount > 0) {
            // Company will pay customer
            $companyPays  = $netAmount;
            $customerPays = 0;
        } else {
            // No payable
            $companyPays  = 0;
            $customerPays = 0;
        }

        return view('registration.booking-returns.show', compact('fileCancellation', 'totalCredit', 'balanceAmount', 'remainingAmount', 'discountValue','customerPays', 'companyPays', 'cancellationsCharges', 'payableAmount'));
    }
}
