<?php

namespace App\Console\Commands;

use App\Models\LandRegistration;
use App\Models\Project;
use App\Models\DetailAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestLandRegistrationPerformance extends Command
{
    protected $signature = 'land-registration:test-performance 
                            {--records=10000 : Number of records to test with}
                            {--iterations=5 : Number of iterations for each test}';
    
    protected $description = 'Test performance of land registration operations with large datasets';

    public function handle()
    {
        $totalRecords = LandRegistration::count();
        $this->info("Testing Land Registration Performance");
        $this->info("Total records in database: " . number_format($totalRecords));
        $this->line(str_repeat('-', 50));

        $this->testCountOperations();
        $this->testPaginationPerformance();
        $this->testFilteringPerformance();
        $this->testRelationshipLoading();
        $this->testAggregationPerformance();
        $this->testSearchPerformance();
        
        $this->info("\nPerformance testing completed!");
        
        return Command::SUCCESS;
    }

    /**
     * Test count operations
     */
    private function testCountOperations(): void
    {
        $this->info("1. COUNT Operations:");
        
        // Simple count
        $start = microtime(true);
        $count = LandRegistration::count();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Simple count: {$count} records | {$time}ms");

        // Count with condition
        $start = microtime(true);
        $countWithCondition = LandRegistration::where('kanal', '>', 50)->count();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Count with condition: {$countWithCondition} records | {$time}ms");

        // Count with relationship
        $start = microtime(true);
        $countWithRelation = LandRegistration::whereHas('project')->count();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Count with relationship: {$countWithRelation} records | {$time}ms");
    }

    /**
     * Test pagination performance
     */
    private function testPaginationPerformance(): void
    {
        $this->info("2. Pagination Performance:");
        
        $pageSizes = [10, 25, 50, 100];
        
        foreach ($pageSizes as $pageSize) {
            $start = microtime(true);
            $results = LandRegistration::paginate($pageSize);
            $time = round((microtime(true) - $start) * 1000, 2);
            $this->line("   - Paginate {$pageSize}: {$time}ms");
        }

        // Pagination with relationships
        $start = microtime(true);
        $results = LandRegistration::with(['project', 'partyAccount'])->paginate(25);
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Paginate 25 with relationships: {$time}ms");
    }

    /**
     * Test filtering performance
     */
    private function testFilteringPerformance(): void
    {
        $this->info("3. Filtering Performance:");
        
        // Single field filter
        $start = microtime(true);
        $filtered = LandRegistration::where('kanal', '>', 50)->get();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Single filter (kanal > 50): {$filtered->count()} records | {$time}ms");

        // Multiple field filters
        $start = microtime(true);
        $filtered = LandRegistration::where('kanal', '>', 50)
            ->where('merla', '>', 10)
            ->where('square_feet', '>', 5000)
            ->get();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Multiple filters: {$filtered->count()} records | {$time}ms");

        // Range filter
        $start = microtime(true);
        $filtered = LandRegistration::whereBetween('total_merla', [100, 1000])->get();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Range filter (total_merla 100-1000): {$filtered->count()} records | {$time}ms");
    }

    /**
     * Test relationship loading performance
     */
    private function testRelationshipLoading(): void
    {
        $this->info("4. Relationship Loading Performance:");
        
        // Without eager loading
        $start = microtime(true);
        $records = LandRegistration::limit(100)->get();
        $records->each(function ($record) {
            $projectName = $record->project->name ?? 'N/A';
            $accountName = $record->partyAccount->name ?? 'N/A';
        });
        $timeWithout = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Without eager loading (100 records): {$timeWithout}ms");

        // With eager loading
        $start = microtime(true);
        $records = LandRegistration::with(['project', 'partyAccount'])->limit(100)->get();
        $records->each(function ($record) {
            $projectName = $record->project->name ?? 'N/A';
            $accountName = $record->partyAccount->name ?? 'N/A';
        });
        $timeWith = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - With eager loading (100 records): {$timeWith}ms");
        
        if ($timeWithout > 0) {
            $savings = round(($timeWithout - $timeWith) / $timeWithout * 100, 2);
            $this->line("   - Eager loading savings: {$savings}%");
        }
    }

    /**
     * Test aggregation performance
     */
    private function testAggregationPerformance(): void
    {
        $this->info("5. Aggregation Performance:");
        
        // Basic aggregations
        $start = microtime(true);
        $stats = LandRegistration::selectRaw('
            COUNT(*) as total,
            AVG(kanal) as avg_kanal,
            AVG(merla) as avg_merla,
            AVG(square_feet) as avg_square_feet,
            AVG(total_merla) as avg_total_merla,
            SUM(total_merla) as sum_total_merla
        ')->first();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Basic aggregations: {$time}ms");
        $this->line("     • Avg Kanal: " . number_format($stats->avg_kanal ?? 0, 2));
        $this->line("     • Avg Total Merla: " . number_format($stats->avg_total_merla ?? 0, 4));
        $this->line("     • Sum Total Merla: " . number_format($stats->sum_total_merla ?? 0, 4));

        // Group by aggregations
        $start = microtime(true);
        $byProject = LandRegistration::groupBy('project_id')
            ->selectRaw('project_id, COUNT(*) as count, SUM(total_merla) as total_merla_sum')
            ->orderBy('total_merla_sum', 'desc')
            ->limit(10)
            ->get();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Group by project (top 10): {$time}ms");
    }

    /**
     * Test search performance
     */
    private function testSearchPerformance(): void
    {
        $this->info("6. Search Performance:");
        
        // Exact match
        $start = microtime(true);
        $exactMatches = LandRegistration::where('khawat_number', 'like', 'KH-100%')->get();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Exact search (khawat_number): {$exactMatches->count()} records | {$time}ms");

        // Partial match
        $start = microtime(true);
        $partialMatches = LandRegistration::where('remarks', 'like', '%land%')->get();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Partial search (remarks): {$partialMatches->count()} records | {$time}ms");

        // Complex search with multiple conditions
        $start = microtime(true);
        $complexSearch = LandRegistration::where(function ($query) {
            $query->where('kanal', '>', 50)
                  ->orWhere('total_merla', '>', 1000);
        })
        ->whereHas('project', function ($query) {
            $query->where('name', 'like', '%project%');
        })
        ->get();
        $time = round((microtime(true) - $start) * 1000, 2);
        $this->line("   - Complex search: {$complexSearch->count()} records | {$time}ms");
    }
}