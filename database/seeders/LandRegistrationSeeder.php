<?php

namespace Database\Seeders;

use App\Models\LandRegistration;
use App\Models\Project;
use App\Models\DetailAccount;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for better performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LandRegistration::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get available data
        $projects = Project::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        // Get party accounts - try different approaches
        $partyAccounts = $this->getPartyAccounts();

        if (empty($projects) || empty($partyAccounts) || empty($users)) {
            $this->command->error('Please seed projects, detail_accounts, and users first!');
            $this->command->info('Projects: ' . count($projects));
            $this->command->info('Party Accounts: ' . count($partyAccounts));
            $this->command->info('Users: ' . count($users));
            return;
        }

        $this->seedLandRegistrations($projects, $partyAccounts, $users);
    }

    /**
     * Get party accounts using multiple fallback approaches
     */
    private function getPartyAccounts(): array
    {
        // Try different approaches to find payable accounts
        $partyAccounts = [];

        // Approach 1: Look for accounts with "payable" in the name
        $partyAccounts = DetailAccount::where('sub_sub_sub_head_id',  6)
            ->pluck('id')
            ->toArray();

        // Approach 2: If no results, try by account type or code
        // if (empty($partyAccounts)) {
        //     $this->command->warn('No payable accounts found by name. Trying by account type/code...');
        //     $partyAccounts = DetailAccount::where('account_type', 'payable')
        //         ->orWhere('account_code', 'like', '2%') // Usually liabilities start with 2
        //         ->orWhere('sub_sub_sub_head_id', 5)
        //         ->pluck('id')
        //         ->toArray();
        // }

        // Approach 3: If still no results, get any available accounts
        if (empty($partyAccounts)) {
            $this->command->warn('No specific accounts found. Using random detail accounts.');
            $partyAccounts = DetailAccount::limit(100)->pluck('id')->toArray();
        }

        // Approach 4: If still empty, create some dummy accounts
        if (empty($partyAccounts)) {
            $this->command->warn('No detail accounts found at all.');
            $partyAccounts = [];
        }

        return $partyAccounts;
    }

    /**
     * Seed the land registration data
     */
    private function seedLandRegistrations(array $projects, array $partyAccounts, array $users): void
    {
        $totalRecords = 10000;
        $batchSize = 1000;
        $batches = ceil($totalRecords / $batchSize);

        $this->command->info('Seeding ' . number_format($totalRecords) . ' land registrations...');

        $progressBar = $this->command->getOutput()->createProgressBar($totalRecords);
        $progressBar->start();

        $faker = \Faker\Factory::create();

        for ($batch = 0; $batch < $batches; $batch++) {
            $landRegistrations = [];

            $recordsInThisBatch = ($batch == $batches - 1)
                ? $totalRecords - ($batch * $batchSize)
                : $batchSize;

            for ($i = 0; $i < $recordsInThisBatch; $i++) {
                // Use array_rand for better performance with large arrays
                $projectId = $projects[array_rand($projects)];
                $partyAccountId = $partyAccounts[array_rand($partyAccounts)];
                $createdBy = $users[array_rand($users)];
                $updatedBy = $users[array_rand($users)];

                $landRegistrations[] = [
                    'project_id' => $projectId,
                    'party_account_id' => $partyAccountId,
                    'khawat_number' => $faker->boolean(60) ? 'KH-' . $faker->numberBetween(1, 500) . '-' . $faker->numberBetween(1, 50) : null,
                    'kanal' => $faker->randomFloat(2, 0, 100),
                    'merla' => $faker->randomFloat(2, 0, 19),
                    'square_feet' => $faker->randomFloat(2, 100, 10000),
                    'total_merla' => 0,
                    'remarks' => $faker->boolean(30) ? $faker->sentence() : null,
                    'created_by' => $createdBy,
                    'updated_by' => $updatedBy,
                    'created_at' => $faker->dateTimeBetween('2020-01-01', '2024-01-01'),
                    'updated_at' => $faker->dateTimeBetween('2020-01-01', '2024-01-01'),
                ];

                $progressBar->advance();
            }

            LandRegistration::insert($landRegistrations);
        }

        $progressBar->finish();
        $this->command->newLine(2);

        $this->calculateTotalMerla();
    }

    /**
     * Calculate total_merla for all records
     */
    private function calculateTotalMerla(): void
    {
        $this->command->info('Calculating total merla for all records...');

        $totalRecords = LandRegistration::count();
        $progressBar = $this->command->getOutput()->createProgressBar($totalRecords);
        $progressBar->start();

        LandRegistration::chunk(1000, function ($landRegistrations) use ($progressBar) {
            foreach ($landRegistrations as $landRegistration) {
                $landRegistration->saveQuietly(); // Use saveQuietly to avoid events
                $progressBar->advance();
            }
        });

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info('Successfully seeded ' . number_format($totalRecords) . ' land registrations!');
    }
}
