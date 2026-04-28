<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('flights')) {
            return;
        }

        $faker = Faker::create();
        $categoryIds = Schema::hasTable('categories') ? DB::table('categories')->where('type', 'flight')->pluck('id')->all() : [];
        $cities = ['Karachi', 'Lahore', 'Islamabad', 'Dubai', 'Jeddah', 'Riyadh', 'Abu Dhabi', 'Doha'];
        $airlines = ['Emirates', 'PIA', 'Qatar Airways', 'FlyDubai', 'Saudi Airlines', 'Etihad'];

        for ($i = 0; $i < 20; $i++) {
            $depCity = $faker->randomElement($cities);
            $arrCity = $faker->randomElement(array_values(array_diff($cities, [$depCity])));
            $depTime = $faker->dateTimeBetween('+1 days', '+45 days');
            $arrTime = (clone $depTime)->modify('+' . $faker->numberBetween(2, 8) . ' hours');

            $row = [
                'airline_name' => $faker->randomElement($airlines),
                'flight_number' => strtoupper($faker->bothify('??###')),
                'departure_city' => $depCity,
                'arrival_city' => $arrCity,
                'departure_time' => $depTime->format('Y-m-d H:i:s'),
                'arrival_time' => $arrTime->format('Y-m-d H:i:s'),
                'category_id' => $categoryIds ? $faker->randomElement($categoryIds) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('flights', 'departure_date')) {
                $row['departure_date'] = $depTime->format('Y-m-d');
            }
            if (Schema::hasColumn('flights', 'return_date')) {
                $row['return_date'] = $faker->boolean(40) ? $faker->dateTimeBetween('+10 days', '+90 days')->format('Y-m-d') : null;
            }
            if (Schema::hasColumn('flights', 'baggage_kg')) {
                $row['baggage_kg'] = $faker->randomElement([20, 25, 30, 35, 40]);
            }
            DB::table('flights')->insert($row);
        }
    }
}

