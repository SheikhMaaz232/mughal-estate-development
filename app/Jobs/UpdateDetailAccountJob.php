<?php

namespace App\Jobs;

use App\Services\DetailAccountService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDetailAccountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

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
    public function handle(DetailAccountService $detailAccountService): void
    {
        try {
            $id = $this->data['id'];
            unset($this->data['id']); // ensure ID isn't overwritten

            $detailAccountService->update($id, $this->data);

            Log::info("DetailAccount ID {$id} updated successfully via Job.");
        } catch (\Exception $e) {
            Log::error('UpdateDetailAccountJob failed: ' . $e->getMessage(), [
                'data'  => $this->data,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
