<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('hotels')) {
            return;
        }

        $faker = Faker::create();
        $cities = ['Makkah', 'Madinah', 'Dubai', 'Abu Dhabi', 'Lahore', 'Karachi', 'Islamabad', 'Jeddah'];

        for ($i = 0; $i < 20; $i++) {
            DB::table('hotels')->insert([
                'name' => $faker->company() . ' Hotel',
                'city' => $faker->randomElement($cities),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

