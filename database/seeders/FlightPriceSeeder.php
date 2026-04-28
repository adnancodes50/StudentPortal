<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FlightPriceSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('flight_prices') || !Schema::hasTable('flights')) {
            return;
        }

        $faker = Faker::create();
        $flightIds = DB::table('flights')->pluck('id')->all();
        if (!$flightIds) {
            return;
        }

        foreach ($flightIds as $flightId) {
            DB::table('flight_prices')->insert([
                'flight_id' => $flightId,
                'seat_class' => $faker->randomElement(['economy', 'business', 'first']),
                'price' => $faker->randomFloat(2, 200, 3000),
                'valid_from' => now()->subDays($faker->numberBetween(1, 10))->toDateString(),
                'valid_to' => now()->addDays($faker->numberBetween(15, 120))->toDateString(),
                'status' => $faker->randomElement(['active', 'inactive']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

