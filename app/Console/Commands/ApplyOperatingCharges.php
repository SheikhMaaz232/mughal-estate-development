<?php

namespace App\Console\Commands;

use App\Models\AccountLedger;
use App\Models\BookingApplication;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ApplyOperatingCharges extends Command
{
    protected $signature = 'charges:apply-operating';
    protected $description = 'Apply monthly operating charges to all bookings (no duplicates)';

    public function handle()
    {
        $today = Carbon::today();
        $this->info("Processing Operating Charges - " . $today->format('d F Y'));

        // Get all active bookings
        $bookings = BookingApplication::with('product', 'detailAccount')
            ->where('condition', 'allow')
            ->where('status', 'Verified')
            ->where('case', '!=', 'ownership_changed')
            ->where('status', '!=', 'Cancelled')
            ->get();

        $totalChargesApplied = 0;

        foreach ($bookings as $booking) {
            $startDate = Carbon::parse($booking->operating_start_date);

            // Skip if booking hasn't started yet
            if ($startDate->gt($today)) {
                continue;
            }

            // Get all months from start date to today's date (same day each month)
            $months = $this->getMonthsToCharge($startDate, $today);

            foreach ($months as $month) {
                $documentNo = 'OP-' . $booking->id . '-' . $month['key'];

                // Check if already charged for this month
                if (AccountLedger::where('document_number', $documentNo)->exists()) {
                    continue; // Skip - already charged
                }

                // Apply charge for this month
                $this->createChargeEntry($booking, $month, $documentNo);
                $totalChargesApplied++;
                $this->line(" Booking #{$booking->id} - Charge applied for {$month['label']}");
            }
        }

        $this->info("Total charges applied: {$totalChargesApplied}");

        return Command::SUCCESS;
    }

    /**
     * Get all months to charge (from start date to today, same day each month)
     *
     * Example:
     *   Start: 15 Jan 2026
     *   Today: 29 Mar 2026
     *
     *   Months: [15 Feb, 15 Mar, (15 Apr = future, skip)]
     */
    private function getMonthsToCharge(Carbon $startDate, Carbon $today): array
    {
        $months = [];
        $currentDate = $startDate->copy()->addMonth();

        while ($currentDate->lte($today)) {
            $months[] = [
                'date' => $currentDate->copy(),
                'key' => $currentDate->format('Ym'),
                'label' => $currentDate->copy()->subMonth()->format('M Y'),
            ];
            $currentDate->addMonth();
        }

        return $months;
    }


    /**
     * Create debit and credit entries for the charge (batch insert for performance)
     */
    private function createChargeEntry($booking, array $month, string $documentNo)
    {
        // Calculate charge amount
        $amount = $booking->operating_charges * $booking->product->total_marla;

        if ($amount <= 0) {
            return;
        }

        // Get income account
        $productAccountId = DetailAccount::where('project_id', $booking->project_id)
            ->where('name_en', $booking->product->name_en)
            ->value('id');

        if (!$productAccountId) {
            return;
        }


        // Prepare base entry data
        $baseEntry = [
            'date' => $month['date'],
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'description_en' => 'Operating Charges - ' . $booking->product->name_en . ' (' . $month['label'] . ')',
            'description_ur' => 'آپریٹنگ چارجز - ' . ($booking->product->name_ur) . ' (' . $month['label'] . ')',
            'document_number' => $documentNo,
        ];

        // Batch insert AccountLedger entries
        $accountLedgerEntries = [
            // DEBIT ENTRY - Customer Account
            array_merge($baseEntry, [
                'party_id' => $booking->detailAccount->party_id,
                'detail_account_id' => $booking->detail_account_id,
                'debit' => $amount,
                'credit' => 0,
            ]),
            // CREDIT ENTRY - Revenue Account
            array_merge($baseEntry, [
                'party_id' => null,
                'detail_account_id' => $productAccountId,
                'debit' => 0,
                'credit' => $amount,
            ]),
            // DEBIT ENTRY - Operating Charges Account (Project-specific)
            array_merge($baseEntry, [
                'party_id' => null,
                'detail_account_id' => $productAccountId,
                'debit' => $amount,
                'credit' => 0,
            ]),

            array_merge($baseEntry, [
                'party_id' => null,
                'detail_account_id' => $booking->operating_receivable_account,
                'debit' => 0,
                'credit' => $amount,
            ]),
        ];

        AccountLedger::insert($accountLedgerEntries);

        GeneralJournal::insert($accountLedgerEntries);
    }
}
