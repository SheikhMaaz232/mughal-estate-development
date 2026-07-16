<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingApplicationRequest;
use App\Jobs\SaveBookingApplicationJob;
use App\Jobs\UpdateBookingApplicationJob;
use App\Models\AccountLedger;
use App\Models\BookingApplication;
use App\Models\BookingNomineeDetail;
use App\Models\BookingPaymentShedule;
use App\Models\BookingReturn;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use App\Models\Product;
use App\Models\StockLedger;
use App\Models\SubSubSubHead;
use App\Services\BookingApplicationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingApplicationController extends Controller
{
    protected $bookingApplicationService;

    public function __construct(BookingApplicationService $bookingApplicationService)
    {
        $this->bookingApplicationService = $bookingApplicationService;
    }

    /**
     * Display a listing of Verified products.
     */
    public function index(Request $request)
    {
        $search = $request->all();

        $products = Product::select(
            'id',
            'project_id',
            'unit_no',
            'name_en',
            'name_ur',
            'total_marla',
            'status',
            'type'
        )->with('project')->where('status', 'Verified')->where('type', '!=', 'item')->search($search)->latest()->paginate(10)->appends(request()->input());

        return view('registration.bookings.verifiedProducts', compact('products', 'search'));
    }

    /**
     * Display a listing of Bookings.
     */
    public function bookingListing(Request $request)
    {
        $search = $request->all();
        $bookings = BookingApplication::with('party', 'detailAccount', 'project', 'product', 'dealer')->search($search)->latest()->paginate(10)->appends(request()->input());

        return view('registration.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new Booking.
     */
    public function create(Request $request)
    {
        $product = Product::where('id', $request->product_id)->first();
        $case = 'First Booking';
        $bookingNo = BookingApplication::generateBookingNo();
        $projectSubSubSubHeads = SubSubSubHead::select('id', 'name_en', 'name_ur')->where('project_id', $product->project_id)->pluck('id');
        $bankAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 19)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $dealerAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 39)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $dealerReceivableAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 1176)->get();
        $possessionAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 4)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $proceedingAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 3)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $developmentChargesAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 9)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $gstAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 7)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $sevenEAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 1265)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $operatingChargesAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 2)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $expenseAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('main_head_id', 4)->where('sub_sub_head_id', 1495)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();

        return view('registration.bookings.create', compact('product', 'bookingNo', 'possessionAccounts', 'operatingChargesAccounts', 'bankAccounts', 'dealerAccounts', 'case', 'dealerReceivableAccounts', 'proceedingAccounts', 'developmentChargesAccounts', 'gstAccounts', 'expenseAccounts', 'sevenEAccounts'));
    }

    public function transfer(Request $request)
    {
        $bookingApplication = BookingApplication::where('id', $request->booking_id)->first();
        $case = 'transfer';
        $product = Product::where('id', $bookingApplication->product_id)->first();
        $bookingNo = BookingApplication::generateBookingNo();
        $projectSubSubSubHeads = SubSubSubHead::select('id', 'name_en', 'name_ur')->where('project_id', $product->project_id)->pluck('id');
        $bankAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_head_id', 2)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $dealerAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 39)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $dealerReceivableAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 1176)->get();
        $transferAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 6)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $transferCharges =  $bookingApplication->total_amount * 0.05;
        $possessionAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 4)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $proceedingAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 3)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $developmentChargesAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 9)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $gstAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 7)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        // $sevenEAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 1265)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $operatingChargesAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 2)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
        $expenseAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('main_head_id', 4)->where('sub_sub_head_id', 1495)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();

        return view('registration.bookings.create', compact('product', 'expenseAccounts', 'bookingApplication', 'bookingNo', 'bankAccounts', 'dealerReceivableAccounts', 'transferAccounts', 'transferCharges', 'dealerAccounts', 'case', 'possessionAccounts', 'proceedingAccounts', 'developmentChargesAccounts', 'gstAccounts', 'operatingChargesAccounts'));
    }

    /**
     * Store a newly created Booking in storage.
     */

    public function store(StoreBookingApplicationRequest $request)
    {
        try {
            if ($request->case === 'First Booking') {

                SaveBookingApplicationJob::dispatch($request->all());
                return redirect()
                    ->route('bookings.index')
                    ->with('success', __('messages.booking_created_successfully'));
            } else {
                DB::transaction(function () use ($request) {

                    $prepared = $this->bookingApplicationService->prepareTransferData($request->all());
                    $booking = BookingApplication::create($prepared['booking']);

                    // 2. Save nominees (only if not empty)
                    if (!empty($prepared['nominees'])) {
                        $nominees = collect($prepared['nominees'])->map(function ($nominee) use ($booking) {
                            $nominee['booking_id'] = $booking->id;
                            $nominee['created_at'] = now();
                            $nominee['updated_at'] = now();
                            return $nominee;
                        })->toArray();

                        BookingNomineeDetail::insert($nominees);
                    }

                    // 4. Save schedules (always process whatever is passed, even one record)
                    $schedules = collect($prepared['schedules'])->map(function ($schedule) use ($booking) {
                        $schedule['booking_id'] = $booking->id;
                        $schedule['created_at'] = now();
                        $schedule['updated_at'] = now();
                        return $schedule;
                    })->toArray();

                    if (!empty($schedules)) {
                        BookingPaymentShedule::insert($schedules);
                    }
                });

                return redirect()
                    ->route('bookings.bookingListing')
                    ->with('success', __('messages.booking_created_successfully'));
            }
        } catch (\Throwable $e) {
            Log::error("Booking creation failed: " . $e->getMessage(), [
                'data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified Booking.
     */
    public function edit($id)
    {
        try {
            $alreadySubmitted = BookingReturn::where('booking_id', $id)->exists();
            if ($alreadySubmitted) {
                return redirect()->back()->with('error',  __('messages.request-already-submitted'));
            }

            $booking = $this->bookingApplicationService->getById($id);
            $product = Product::where('id', $booking->product_id)->first();
            $nominees = BookingNomineeDetail::where('booking_id', $booking->id)->get();
            $schedules = BookingPaymentShedule::where('booking_id', $booking->id)->get();
            $projectSubSubSubHeads = SubSubSubHead::select('id', 'name_en', 'name_ur')->where('project_id', $product->project_id)->pluck('id');
            $bankAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_head_id', 2)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $dealerAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 39)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $dealerReceivableAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 1176)->get();
            $possessionAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 4)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $proceedingAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 3)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $developmentChargesAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 9)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $gstAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 7)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $sevenEAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 1265)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $operatingChargesAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('sub_sub_head_id', 2)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();
            $expenseAccounts = DetailAccount::select('id', 'name_en', 'name_ur')->where('main_head_id', 4)->where('sub_sub_head_id', 1495)->whereIn('sub_sub_sub_head_id', $projectSubSubSubHeads)->get();

            return view('registration.bookings.edit', compact('booking', 'dealerAccounts', 'nominees', 'schedules', 'dealerReceivableAccounts', 'possessionAccounts', 'proceedingAccounts', 'developmentChargesAccounts', 'gstAccounts', 'sevenEAccounts', 'operatingChargesAccounts', 'bankAccounts', 'expenseAccounts'));
        } catch (\Exception $e) {
            return redirect()->route('bookings.bookingListing')->with('error', __('messages.unexpected-error'));
        }
    }

    public function update(StoreBookingApplicationRequest $request, $id)
    {
        try {

            if ($request->case === 'First Booking') {
                UpdateBookingApplicationJob::dispatch($id, $request->all());
            } else {
                DB::transaction(function () use ($request, $id) {

                    $prepared = $this->bookingApplicationService->prepareTransferUpdateData($request->all(), $id);
                    $booking = BookingApplication::findOrFail($id);
                    $booking->update($prepared['booking']);

                    if ($booking->status == 'Verified') {
                        $previousBooking = BookingApplication::lockForUpdate()->findOrFail($booking->previous_booking_id);
                        $documentNo = 'B-A' . '-' . $booking->id;
                        AccountLedger::where('invoice_id', $booking->id)->where('document_number', $documentNo)->delete();
                        GeneralJournal::where('invoice_id', $booking->id)->where('document_number', $documentNo)->delete();
                        StockLedger::where('invoice_id', $booking->id)->where('document_number', $documentNo)->where('product_id', $booking->product_id)->delete();

                        $this->bookingApplicationService->createTransferCaseLedgerEntry($booking, $previousBooking);
                    }

                    // 3. Refresh nominees
                    BookingNomineeDetail::where('booking_id', $booking->id)->delete();

                    if (!empty($prepared['nominees'])) {
                        $nominees = collect($prepared['nominees'])->map(function ($nominee) use ($booking) {
                            $nominee['booking_id'] = $booking->id;
                            $nominee['created_at'] = now();
                            $nominee['updated_at'] = now();

                            return $nominee;
                        })->toArray();

                        BookingNomineeDetail::insert($nominees);
                    }

                    // 5. Refresh schedules
                    BookingPaymentShedule::where('booking_id', $booking->id)->delete();

                    if (!empty($prepared['schedules'])) {
                        $schedules = collect($prepared['schedules'])->map(function ($schedule) use ($booking) {
                            $schedule['booking_id'] = $booking->id;
                            $schedule['created_at'] = now();
                            $schedule['updated_at'] = now();
                            return $schedule;
                        })->toArray();

                        BookingPaymentShedule::insert($schedules);
                    }
                });
            }

            return redirect()
                ->route('bookings.bookingListing')
                ->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            Log::error(" Booking update failed: " . $e->getMessage(), [
                'booking_id' => $id,
                'data'       => $request->all(),
            ]);

            return redirect()
                ->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified Booking from storage.
     */
    public function destroy($id)
    {
        try {
            $this->bookingApplicationService->delete($id);
            return redirect()->route('Bookings.bookingListing')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('Bookings.bookingListing')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified BookingApplication details.
     *
     * @param  \App\Models\BookingApplication  $bookingApplicationData
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */

    public function show($id)
    {
        // Fetch booking data with relationships
        $bookingData = BookingApplication::with('party', 'detailAccount', 'project', 'product', 'dealer')
            ->where('id', $id)
            ->first();

        $bookingNo = BookingApplication::bookingNo($id);

        $bookingNomineeData = null;
        $bookingPaymentSchedule = collect();
        $advancePaymentScheduleData = null;
        $installmentPaymentScheduleData = null;
        $duePaymentScheduleData = null;
        $transferCharges = 0;
        $previousBooking = null;
        $advancePayment = 0;
        $bookingReceivedAmount = 0;
        $remainingBalance = 0;
        $installmentPayment = 0;
        $installmentPayAmount = 0;
        $installmentArrayCount = 0;
        $duePayment = 0;
        $duePayAmount = 0;
        $duePaymentArrayCount = 0;
        $permarlaValue = 0;
        $expandedSchedules = collect();
        $grandTotal = 0;
        $bookingApplicationData = null;
        $bookingPaymentSchedules = collect();

        if ($bookingData) {

            // Nominee data
            $bookingNomineeData = BookingNomineeDetail::with('relation', 'nomineeParty')
                ->where('booking_id', $bookingData->id)
                ->first();

            // Payment schedules
            $bookingPaymentSchedule = BookingPaymentShedule::with('scheduleType', 'schedulePeriod')
                ->where('booking_id', $bookingData->id)
                ->get();

            // Per marla value
            $permarlaValue = $bookingData->product->total_marla > 0
                ? $bookingData->total_amount / $bookingData->product->total_marla
                : 0;

            // If payment schedules exist
            if ($bookingPaymentSchedule->isNotEmpty()) {

                // Advance Payment
                $advancePaymentScheduleData = $bookingPaymentSchedule
                    ->where('schedule_type_id', 1)
                    ->sortBy('due_date')
                    ->first();
                $advancePayment = $bookingPaymentSchedule
                    ->where('schedule_type_id', 1)
                    ->sum('calculated_total_amount');

                // Installment Payment
                $installmentCollection = $bookingPaymentSchedule->where('schedule_type_id', 2);
                $installmentPaymentScheduleData = $installmentCollection->sortBy('due_date')->first();
                $installmentArrayCount = $installmentCollection->sum('number');
                $installmentPayment = $installmentCollection->sum('calculated_total_amount');
                $installmentPayAmount = $installmentCollection->sum('pay_amount');

                // Due Payment
                $dueCollection = $bookingPaymentSchedule->where('schedule_type_id', 3);
                $duePaymentScheduleData = $dueCollection->sortBy('due_date')->first();
                $duePaymentArrayCount = $dueCollection->value('number');
                $duePayment = $dueCollection->sum('calculated_total_amount');
                $duePayAmount = $dueCollection->sum('pay_amount');

                // Original booking application and schedules
                $bookingApplicationData = BookingApplication::where('id', $id)->first();
                $bookingPaymentSchedules = BookingPaymentShedule::with('scheduleType', 'schedulePeriod')
                    ->where('booking_id', $id)->get();

                // Merge the "expanded schedules" logic from the second function
                foreach ($bookingPaymentSchedules as $schedule) {

                    $startDate = Carbon::parse($schedule->due_date);
                    $numberOfPayments = $schedule->number;
                    $amount = $schedule->pay_amount;

                    $intervalType = strtolower($schedule->schedulePeriod->title_en);

                    for ($i = 0; $i < $numberOfPayments; $i++) {

                        $dueDate = $startDate->copy();


                        switch (strtolower(trim($intervalType))) {

                            case 'monthly':
                                $dueDate->addMonths($i);
                                break;

                            case 'quarter':
                            case 'quarterly':
                                $dueDate->addMonths($i * 3);
                                break;

                            case 'half year':
                            case 'half-year':
                            case 'half yearly':
                                $dueDate->addMonths($i * 6);
                                break;

                            case 'yearly':
                            case 'year':
                            case 'annual':
                                $dueDate->addYears($i);
                                break;

                            case 'nine monthly':
                                $dueDate->addMonths($i * 9);
                                break;

                            case 'one time':
                            default:
                                if ($i > 0) {
                                    break 2; // exit loop
                                }
                                break;
                        }

                        $expandedSchedules->push((object)[
                            'type' => $schedule->scheduleType->title_en,
                            'period' => $schedule->schedulePeriod->title_en,
                            'due_date' => $dueDate,
                            'number' => 1,
                            'pay_amount' => $amount,
                            'total' => $amount
                        ]);

                        $grandTotal += $amount;
                    }
                }

                $expandedSchedules = $expandedSchedules->sortBy('due_date')->values();
            }

            // Transfer case
            if ($bookingData->case === 'transfer' && $bookingData->previous_booking_id) {
                $previousBooking = BookingApplication::where('id', $bookingData->previous_booking_id)->first();
                $transferCharges = $previousBooking->transfer_charges;
                $bookingReceivedAmount = AccountLedger::where('detail_account_id', $previousBooking->detail_account_id)->where('transaction_type', 'booking_payment')->sum('credit');
                $remainingBalance = $previousBooking->total_amount - $bookingReceivedAmount;
            }
        }

        return view('registration.bookings.show', compact(
            'bookingData',
            'bookingNo',
            'bookingNomineeData',
            'advancePaymentScheduleData',
            'installmentArrayCount',
            'duePaymentScheduleData',
            'duePayAmount',
            'installmentPaymentScheduleData',
            'duePaymentArrayCount',
            'duePayment',
            'remainingBalance',
            'bookingReceivedAmount',
            'transferCharges',
            'previousBooking',
            'permarlaValue',
            'installmentPayAmount',
            'installmentPayment',
            'advancePayment',
            'bookingPaymentSchedules',
            'bookingApplicationData',
            'grandTotal',
            'expandedSchedules'
        ));
    }


    public function updateStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $booking = BookingApplication::lockForUpdate()->findOrFail($id);

            if ($booking->case === 'transfer') {

                $request->validate([
                    'status' => 'required|in:Verified,Unverified',
                    'case' => 'required|in:ownership_changed,Verified',
                ]);

                $previousBooking = BookingApplication::lockForUpdate()->findOrFail($booking->previous_booking_id);
                $previousBooking->case = $request->case;
                $previousBooking->save();

                $booking->status = $request->status;
                $booking->save();

                $documentNo = 'B-A' . '-' . $booking->id;
                // Only create ledger entries if they don’t already exist
                $ledgerExists = AccountLedger::where('invoice_id', $booking->id)->where('document_number', $documentNo)->exists();
                $journalExists = GeneralJournal::where('invoice_id', $booking->id)->where('document_number', $documentNo)->exists();

                if (!$ledgerExists && !$journalExists) {
                    $this->bookingApplicationService->createTransferCaseLedgerEntry($booking, $previousBooking);
                }
            } else {
                $request->validate([
                    'status' => 'required|in:Verified,Unverified',
                ]);
                //  Prevent duplicate processing
                if ($booking->status === 'Verified') {
                    DB::rollBack();
                    return redirect()->route('bookings.bookingListing')
                        ->with('info', 'This booking is already verified.');
                }

                //  Update status
                $booking->status = $request->status;
                $booking->save();

                // Fetch related payments
                $bookingPayments = BookingPaymentShedule::where('booking_id', $booking->id)->get();
                $documentNo = 'B-A' . '-' . $booking->id;
                //  Only create ledger entries if they don’t already exist
                $ledgerExists = AccountLedger::where('invoice_id', $booking->id)->where('document_number', $documentNo)->exists();
                $journalExists = GeneralJournal::where('invoice_id', $booking->id)->where('document_number', $documentNo)->exists();

                if (!$ledgerExists && !$journalExists) {
                    $this->bookingApplicationService->createLedgerEntry($booking, $bookingPayments);
                }
            }

            DB::commit();

            return redirect()->route('bookings.bookingListing')
                ->with('success', __('messages.record-updated'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(' Booking verification failed', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('bookings.bookingListing')
                ->with('error', 'An error occurred while verifying the booking. Please try again.');
        }
    }

    public function scheduleCreate($id)
    {
        $bookingData = BookingApplication::where('id', $id)->first();
        $bookingPaymentSchedules = BookingPaymentShedule::with('scheduleType', 'schedulePeriod')->where('booking_id', $id)->get();
        $grandTotal = $bookingPaymentSchedules->sum('calculated_total_amount');

        return view('registration.bookings.schedule', compact('bookingPaymentSchedules', 'bookingData', 'grandTotal'));
    }

    public function preClearanceLetter($id)
    {
        $bookingApplication = BookingApplication::with(['project', 'product', 'detailAccount'])->findOrFail($id);

        $detailAccountId = $bookingApplication->detail_account_id;
        $bookingValue = $bookingApplication->total_amount;
        $developmentCharges = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'development_charges')->sum('debit');
        $proceedingCharges = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'proceeding_fees')->sum('debit');
        $gstCharges = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'gst')->sum('debit');
        $operatingExpense = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'operating_charges')->sum('debit');
        $registryFees = AccountLedger::where('detail_account_id', $detailAccountId)->where('transaction_type', 'registry_fees')->sum('debit');
        $possessionFees = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'possession_fees')->sum('debit');
        $totalCredit = AccountLedger::where('detail_account_id', $detailAccountId)->sum('credit');
        $totalDebit = AccountLedger::where('detail_account_id', $detailAccountId)->sum('debit');
        $grandTotal = $bookingValue + $developmentCharges + $proceedingCharges + $gstCharges + $operatingExpense + $registryFees + $possessionFees;
        $remainingAmount =  $totalDebit - $totalCredit;
        $payableAmount = $bookingValue - $developmentCharges;

        return view('registration.bookings.pre-clearanceLetter', compact('bookingApplication', 'grandTotal', 'possessionFees', 'registryFees', 'operatingExpense', 'gstCharges', 'developmentCharges', 'proceedingCharges', 'totalCredit', 'remainingAmount', 'payableAmount'));
    }

    public function clearanceLetter($id)
    {
        $bookingApplication = BookingApplication::with(['project', 'product', 'detailAccount'])->findOrFail($id);

        $detailAccountId = $bookingApplication->detail_account_id;
        $bookingValue = $bookingApplication->total_amount;
        $developmentCharges = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'development_charges')->sum('debit');
        $proceedingCharges = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'proceeding_fees')->sum('debit');
        $gstCharges = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'gst')->sum('debit');
        $operatingExpense = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'operating_charges')->sum('debit');
        $registryFees = AccountLedger::where('detail_account_id', $detailAccountId)->where('transaction_type', 'registry_fees')->sum('debit');
        $possessionFees = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'possession_fees')->sum('debit');
        $feesDiscount = AccountLedger::where('detail_account_id', $detailAccountId)->where('document_number', 'B-A' . '-' . $bookingApplication->id)->where('transaction_type', 'feeses_discount')->sum('credit');
        $totalCredit = AccountLedger::where('detail_account_id', $detailAccountId)->sum('credit');
        $totalDebit = AccountLedger::where('detail_account_id', $detailAccountId)->sum('debit');
        $grandTotal = $bookingValue + $developmentCharges + $proceedingCharges + $gstCharges + $operatingExpense + $registryFees + $possessionFees;
        $remainingAmount =  $totalDebit - $totalCredit;

        if ($remainingAmount != 0) {
            return redirect()->back()->with('error',  __('messages.dues_pending'));
        }

        return view('registration.bookings.clearanceLetter', compact('bookingApplication', 'feesDiscount', 'grandTotal', 'possessionFees', 'registryFees', 'operatingExpense', 'gstCharges', 'developmentCharges', 'proceedingCharges', 'totalCredit', 'remainingAmount'));
    }


    /**
     * Return Detail-Accounts list for given Party (used in dependent dropdown).
     */
    public function getDetailAccountForParty($partyId)
    {
        try {
            $detailAccounts = $this->bookingApplicationService->getDetailAccountForMainParty($partyId);
            if ($detailAccounts) {
                return response()->json(['status' => 'success', 'data' => $detailAccounts]);
            }
            return response()->json(['status' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => []]);
        }
    }
}
