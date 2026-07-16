<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class DealerFactory extends Factory
{
    public function definition()
    {
        // Generate sample dealer photo (50% chance)
        $photo = null;
        if ($this->faker->boolean(50)) {
            $photo = 'dealers/' . $this->faker->image(
                storage_path('app/public/dealers'),
                200, 200, 'people', false
            );
        }

        return [
            'name_en' => $this->faker->company,
            'name_ur' => $this->faker->company, // In a real app, you might use Arabic text here
            'address_en' => $this->faker->address,
            'address_ur' => $this->faker->address, // In a real app, use Urdu/Arabic text
            'mobile_number' => $this->faker->phoneNumber,
            'phone_number' => $this->faker->optional()->phoneNumber,
            'photo' => $photo,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
