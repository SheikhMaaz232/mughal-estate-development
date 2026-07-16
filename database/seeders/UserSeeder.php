<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use OwenIt\Auditing\Events\Auditing;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::withoutEvents(function () {
            User::create([
                'name_en' => 'Super Admin',
                'name_ur' => 'سپر ایڈمن',
                'father_name_en' => 'Admin Father',
                'father_name_ur' => 'ایڈمن والد',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        });
    }

}
