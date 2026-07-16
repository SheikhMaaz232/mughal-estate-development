<?php

namespace App\Jobs;

use App\Models\AccountLedger;
use Illuminate\Bus\Queueable;
use App\Models\GeneralJournal;
use App\Models\BookingApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BookingNomineeDetail;
use App\Models\BookingPaymentShedule;
use App\Models\StockLedger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\BookingApplicationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBookingApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $bookingId;
    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(int $bookingId, array $data)
    {
        $this->bookingId = $bookingId;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(BookingApplicationService $service): void
    {
        try {
            $start = microtime(true);
            $prepared = $service->prepareUpdateData($this->data, $this->bookingId);
            DB::transaction(function () use ($prepared) {
                // 1. Find booking
                $booking = BookingApplication::findOrFail($this->bookingId);

                $booking->update($prepared['booking']);

                if ($booking->status == 'Verified') {
                    $documentNo = 'B-A' . '-' . $booking->id;
                    AccountLedger::where('invoice_id', $booking->id)->where('document_number', $documentNo)->delete();
                    GeneralJournal::where('invoice_id', $booking->id)->where('document_number', $documentNo)->delete();
                    StockLedger::where('invoice_id', $booking->id)->where('document_number', $documentNo)->where('product_id', $booking->product_id)->delete();
                    AccountLedger::create($prepared['commissionCreditData']);
                    AccountLedger::create($prepared['commissionDebitData']);
                    AccountLedger::create($prepared['receivableCommissionCreditData']);
                    AccountLedger::create($prepared['receivableCommissionDebitData']);
                    AccountLedger::create($prepared['creditData']);
                    GeneralJournal::create($prepared['generalJournalsCommissionCreditData']);
                    GeneralJournal::create($prepared['generalJournalsCommissionDebitData']);
                    GeneralJournal::create($prepared['receivableGeneralJournalsCommissionCreditData']);
                    GeneralJournal::create($prepared['receivableGeneralJournalsCommissionDebitData']);
                    GeneralJournal::create($prepared['generalJournalCreditData']);
                    StockLedger::create($prepared['stockLedgerData']);
                    if (!empty($prepared['discountCreditData'])) {
                        AccountLedger::create($prepared['discountCreditData']);
                        GeneralJournal::create($prepared['discountCreditData']);
                    }

                    if (!empty($prepared['discountDebitData'])) {
                        AccountLedger::create($prepared['discountDebitData']);
                        GeneralJournal::create($prepared['discountDebitData']);
                    }

                    // // 7E Chalan
                    // if (!empty($prepared['sevenEChalanEntries'])) {
                    //     AccountLedger::insert($prepared['sevenEChalanEntries']);
                    //     GeneralJournal::insert($prepared['sevenEChalanEntries']);
                    // }

                    // GST
                    if (!empty($prepared['gstEntries'])) {
                        AccountLedger::insert($prepared['gstEntries']);
                        GeneralJournal::insert($prepared['gstEntries']);
                    }

                    // Development Charges
                    if (!empty($prepared['developmentChargesEntries'])) {
                        AccountLedger::insert($prepared['developmentChargesEntries']);
                        GeneralJournal::insert($prepared['developmentChargesEntries']);
                    }

                    // Proceeding Fees
                    if (!empty($prepared['proceedingFeesEntries'])) {
                        AccountLedger::insert($prepared['proceedingFeesEntries']);
                        GeneralJournal::insert($prepared['proceedingFeesEntries']);
                    }

                    // Possession Fees
                    if (!empty($prepared['possessionFeesEntries'])) {
                        AccountLedger::insert($prepared['possessionFeesEntries']);
                        GeneralJournal::insert($prepared['possessionFeesEntries']);
                    }

                    $debitData = collect($prepared['debitData'])->map(function ($debitAccountData) use ($booking) {
                        $debitAccountData['invoice_id'] = $booking->id;
                        $debitAccountData['created_at'] = now();
                        $debitAccountData['updated_at'] = now();
                        return $debitAccountData;
                    })->toArray();

                    if (!empty($debitData)) {
                        AccountLedger::insert($debitData);
                    }

                    $generalJournalDebitEntry = collect($prepared['generalJournalDebitData'])->map(function ($generalJournalDebitAccountEntry) use ($booking) {
                        $generalJournalDebitAccountEntry['invoice_id'] = $booking->id;
                        $generalJournalDebitAccountEntry['created_at'] = now();
                        $generalJournalDebitAccountEntry['updated_at'] = now();
                        return $generalJournalDebitAccountEntry;
                    })->toArray();

                    if (!empty($generalJournalDebitEntry)) {
                        GeneralJournal::insert($generalJournalDebitEntry);
                    }
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

            $end = microtime(true);
            $timeTaken = $end - $start;

            Log::info("✅ UpdateBookingApplicationJob executed in {$timeTaken} seconds for booking ID {$this->bookingId}");
        } catch (\Throwable $e) {
            Log::error("❌ UpdateBookingApplicationJob failed in handle(): " . $e->getMessage(), [
                'booking_id' => $this->bookingId,
                'data'       => $this->data,
                'trace'      => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Called automatically when the job fails permanently.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("💥 UpdateBookingApplicationJob permanently failed", [
            'booking_id' => $this->bookingId,
            'reason'     => $exception->getMessage(),
            'data'       => $this->data,
        ]);
    }
}
