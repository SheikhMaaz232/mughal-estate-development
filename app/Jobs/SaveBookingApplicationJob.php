<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use Illuminate\Bus\Queueable;
use App\Models\GeneralJournal;
use App\Models\BookingApplication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BookingNomineeDetail;
use App\Models\BookingPartnerDetail;
use App\Models\BookingPaymentShedule;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\BookingApplicationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveBookingApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */

    public function handle(BookingApplicationService $service): void
    {
        try {
            $start = microtime(true);

            $prepared = $service->prepareData($this->data);

            DB::transaction(function () use ($prepared) {
                if (isset($this->data['product_id'])) {
                    $product = Product::find($this->data['product_id']);
                    if ($product) {
                        $product->update(['status' => 'Booked']);
                    }
                }
                // 1. Save booking
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

            $end = microtime(true);
            $timeTaken = $end - $start;

            Log::info("✅ SaveBookingApplicationJob executed in {$timeTaken} seconds");
        } catch (\Throwable $e) {
            Log::error("❌ SaveBookingApplicationJob failed in handle(): " . $e->getMessage(), [
                'data'  => $this->data,
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * This method is called automatically when the job fails permanently.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical("💥 SaveBookingApplicationJob permanently failed", [
            'reason' => $exception->getMessage(),
            'data'   => $this->data,
        ]);
    }
}
