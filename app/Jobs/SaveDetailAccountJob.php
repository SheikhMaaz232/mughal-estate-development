<?php

namespace App\Jobs;

use App\Services\DetailAccountService;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveDetailAccountJob implements ShouldQueue
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
    public function handle(DetailAccountService $detailAccountService)
    {
        // Save using the service


        $start = microtime(true);
        $detailAccountService->create($this->data);
        $end = microtime(true);
        $timeTaken = $end - $start;

        Log::info("Job SaveDetailAccountJob executed in {$timeTaken} seconds");
    }
}
