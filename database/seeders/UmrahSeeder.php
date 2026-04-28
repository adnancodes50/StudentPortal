<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UmrahSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('umrah_packages')) {
            return;
        }

        $faker = Faker::create();
        $packageIds = [];
        for ($i = 0; $i < 20; $i++) {
            $dep = $faker->dateTimeBetween('+5 days', '+120 days');
            $days = $faker->randomElement([7, 10, 14, 21]);
            $packageIds[] = DB::table('umrah_packages')->insertGetId([
                'package_name' => 'Umrah ' . $faker->words(2, true),
                'total_days' => $days,
                'price_per_person' => $faker->randomFloat(2, 800, 5000),
                'makkah_hotel' => $faker->company() . ' Makkah',
                'madinah_hotel' => $faker->company() . ' Madinah',
                'group_size' => $faker->numberBetween(10, 45),
                'departure_date' => $dep->format('Y-m-d'),
                'return_date' => (clone $dep)->modify("+{$days} days")->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!Schema::hasTable('umrah_bookings') || !Schema::hasTable('bookings') || !$packageIds) {
            return;
        }
        $bookingIds = DB::table('bookings')->pluck('id')->all();
        if (!$bookingIds) {
            return;
        }
        foreach (array_slice($bookingIds, 0, 30) as $bookingId) {
            DB::table('umrah_bookings')->updateOrInsert(
                ['booking_id' => $bookingId],
                ['package_id' => $faker->randomElement($packageIds), 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}

