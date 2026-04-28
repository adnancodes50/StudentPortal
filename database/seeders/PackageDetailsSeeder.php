<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PackageDetailsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('package_details') || !Schema::hasTable('packages')) {
            return;
        }

        $faker = Faker::create();
        $packageIds = DB::table('packages')->pluck('id')->all();
        if (!$packageIds) {
            return;
        }

        $flightIds = Schema::hasTable('flights') ? DB::table('flights')->pluck('id')->all() : [];
        $hotelIds = Schema::hasTable('hotels') ? DB::table('hotels')->pluck('id')->all() : [];
        $roomIds = Schema::hasTable('rooms') ? DB::table('rooms')->pluck('id')->all() : [];

        foreach ($packageIds as $packageId) {
            DB::table('package_details')->insert([
                'package_id' => $packageId,
                'flight_id' => $flightIds ? $faker->randomElement($flightIds) : null,
                'hotel_id' => $hotelIds ? $faker->randomElement($hotelIds) : null,
                'room_id' => $roomIds ? $faker->randomElement($roomIds) : null,
                'nights' => $faker->numberBetween(2, 10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

