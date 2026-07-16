<?php

namespace Database\Seeders;

use App\Models\Dealer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DealerSeeder extends Seeder
{
    public function run()
    {
        // Clear existing dealers
        Dealer::truncate();

        // Create directory for dealer photos if it doesn't exist
        if (!Storage::exists('public/dealers')) {
            Storage::makeDirectory('public/dealers');
        }

        // Generate 50 fake dealers
        Dealer::factory()->count(50)->create();
    }
}
