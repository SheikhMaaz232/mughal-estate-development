<?php
// app/Console/Commands/TestDatabasePerformance.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabasePerformance extends Command
{
    protected $signature = 'test:db-performance';
    protected $description = 'Test database and query performance';

    public function handle()
    {
        $this->info("Database Performance Test");
        $this->line(str_repeat('=', 60));

        $this->testQueryPerformance();
        $this->testIndexUsage();
        $this->testConnectionPerformance();
        
        return Command::SUCCESS;
    }

    private function testQueryPerformance(): void
    {
        $this->info("Query Performance Tests:");
        
        // Test various query types
        $queries = [
            'Simple SELECT' => 'SELECT COUNT(*) FROM land_registrations',
            'SELECT with WHERE' => 'SELECT * FROM land_registrations WHERE kanal > 50 LIMIT 100',
            'SELECT with JOIN' => 'SELECT lr.*, p.name as project_name FROM land_registrations lr JOIN projects p ON lr.project_id = p.id LIMIT 100',
            'Aggregation' => 'SELECT project_id, COUNT(*), AVG(total_merla) FROM land_registrations GROUP BY project_id',
            'Complex WHERE' => 'SELECT * FROM land_registrations WHERE kanal > 50 AND merla > 10 AND square_feet > 5000 LIMIT 100',
        ];

        foreach ($queries as $name => $query) {
            $start = microtime(true);
            $result = DB::select($query);
            $time = round((microtime(true) - $start) * 1000, 2);
            $this->line("   - {$name}: {$time}ms");
        }
    }

    private function testIndexUsage(): void
    {
        $this->info("Index Usage Analysis:");
        
        // Check if queries are using indexes
        $queries = [
            'EXPLAIN SELECT * FROM land_registrations WHERE kanal > 50',
            'EXPLAIN SELECT * FROM land_registrations WHERE project_id = 1',
            'EXPLAIN SELECT * FROM land_registrations WHERE khawat_number LIKE "KH-100%"',
        ];

        foreach ($queries as $query) {
            $result = DB::select($query);
            $this->line("   - Query: " . substr($query, 7, 50) . "...");
            $this->line("     • Type: " . $result[0]->type);
            $this->line("     • Possible keys: " . $result[0]->possible_keys);
            $this->line("     • Key: " . $result[0]->key);
        }
    }

    private function testConnectionPerformance(): void
    {
        $this->info("Connection Performance:");
        
        // Test connection speed
        $iterations = 10;
        $totalTime = 0;

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            DB::select('SELECT 1');
            $totalTime += (microtime(true) - $start);
        }

        $avgTime = round(($totalTime / $iterations) * 1000, 2);
        $this->line("   - Average connection + simple query: {$avgTime}ms");
    }
}