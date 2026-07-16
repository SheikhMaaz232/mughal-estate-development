<?php

namespace Database\Seeders;

use App\Models\Facing;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FacingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facings = [
            ['name_en' => 'Main Road Facing', 'name_ur' => 'مین روڈ فیسنگ'],
            ['name_en' => 'Main Road Facing + Corner Facing', 'name_ur' => 'مین روڈ فیسنگ + کارنر فیسنگ '],
            ['name_en' => 'Main Road Facing + Park Facing', 'name_ur' => 'مین روڈ فیسنگ + پارک فیسنگ'],
            ['name_en' => 'Mosque Facing + Main Road Facing + Corner Facing + Park Facing', 'name_ur' => 'مسجد فیسنگ + مین روڈ فیسنگ + کارنر فیسنگ + پارک فیسنگ'],
            ['name_en' => 'Main Road Facing + Mosque Facing', 'name_ur' => 'مین روڈ فیسنگ + مسجد فیسنگ '],
            ['name_en' => 'Corner Facing', 'name_ur' => 'کارنر فیسنگ'],
            ['name_en' => 'Park Facing + Corner Facing', 'name_ur' => 'کارنر فیسنگ + پارک فیسنگ'],
            ['name_en' => 'Mosque Facing + Corner Facing', 'name_ur' => 'کارنر فیسنگ + مسجد فیسنگ'],
            ['name_en' => 'Double Corner Facing', 'name_ur' => 'ڈبل کارنر فیسنگ'],
            ['name_en' => 'Double Corner Facing + Park Facing', 'name_ur' => 'ڈبل کارنر فیسنگ + پارک فیسنگ'],
            ['name_en' => 'Park Facing', 'name_ur' => 'پارک فیسنگ'],
            ['name_en' => 'Mosque Facing', 'name_ur' => 'مسجد فیسنگ'],
            ['name_en' => 'Mosque Facing + Main Road Facing', 'name_ur' => ' مسجد فیسنگ + مین روڈ فیسنگ'],
            ['name_en' => 'In line', 'name_ur' => 'لائن میں'],
            ['name_en' => 'Public Building Facing', 'name_ur' => 'پبلک بلڈنگ فیسنگ'],
            ['name_en' => 'Public Building Facing + Corner Facing', 'name_ur' => 'پبلک بلڈنگ فیسنگ + کارنر فیسنگ '],
            ['name_en' => 'Public Building Facing + Corner Facing + Park Facing', 'name_ur' => 'پبلک بلڈنگ فیسنگ + کارنر فیسنگ + پارک فیسنگ'],
            ['name_en' => 'Commercial Facing', 'name_ur' => 'کمرشل فیسنگ  '],
            ['name_en' => 'Commercial Facing + Corner Facing', 'name_ur' => 'کمرشل فیسنگ + کارنر فیسنگ '],
            ['name_en' => 'Commercial Facing + Park Facing', 'name_ur' => 'کمرشل فیسنگ + پارک فیسنگ '],
            ['name_en' => 'Commercial Facing + Park Facing + Mosque Facing', 'name_ur' => 'کمرشل فیسنگ + پارک فیسنگ + مسجد فیسنگ '],
            ['name_en' => 'Commercial Facing + Mosque Facing', 'name_ur' => 'کمرشل فیسنگ + مسجد فیسنگ '],
            ['name_en' => 'Park Facing + Mosque Facing', 'name_ur' => 'پارک فیسنگ + مسجد فیسنگ '],
            ['name_en' => 'Main Road Facing + Park Facing + Corner Facing', 'name_ur' => 'مین روڈ فیسنگ + پارک فیسنگ + کارنر فیسنگ'],
            ['name_en' => 'Commercial Facing + Main Road Facing', 'name_ur' => 'کمرشل فیسنگ + مین روڈ فیسنگ '],
        ];

        foreach ($facings as $facing) {
            Facing::create($facing);
        }
    }
}
