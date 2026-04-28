<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BookingRelationsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('bookings')) {
            return;
        }

        $faker = Faker::create();
        $bookingIds = DB::table('bookings')->pluck('id')->all();
        if (!$bookingIds) {
            return;
        }

        if (Schema::hasTable('booking_passengers')) {
            foreach (array_slice($bookingIds, 0, 60) as $bookingId) {
                for ($i = 0; $i < 1; $i++) {
                    DB::table('booking_passengers')->insert([
                        'booking_id' => $bookingId,
                        'name' => $faker->name(),
                        'passport_no' => strtoupper($faker->bothify('P#######')),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if (Schema::hasTable('booking_flights') && Schema::hasTable('flights')) {
            $flightIds = DB::table('flights')->pluck('id')->all();
            if ($flightIds) {
                foreach (array_slice($bookingIds, 0, 40) as $bookingId) {
                    DB::table('booking_flights')->insert([
                        'booking_id' => $bookingId,
                        'flight_id' => $faker->randomElement($flightIds),
                        'price' => $faker->randomFloat(2, 200, 3000),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if (Schema::hasTable('booking_hotels') && Schema::hasTable('rooms')) {
            $rooms = DB::table('rooms')->select(['id', 'hotel_id'])->get();
            if ($rooms->isNotEmpty()) {
                foreach (array_slice($bookingIds, 0, 40) as $bookingId) {
                    $room = $rooms->random();
                    DB::table('booking_hotels')->insert([
                        'booking_id' => $bookingId,
                        'hotel_id' => $room->hotel_id,
                        'room_id' => $room->id,
                        'price' => $faker->randomFloat(2, 50, 500),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        if (Schema::hasTable('booking_packages') && Schema::hasTable('packages')) {
            $packageIds = DB::table('packages')->pluck('id')->all();
            if ($packageIds) {
                foreach (array_slice($bookingIds, 0, 40) as $bookingId) {
                    DB::table('booking_packages')->insert([
                        'booking_id' => $bookingId,
                        'package_id' => $faker->randomElement($packageIds),
                        'price' => $faker->randomFloat(2, 300, 8000),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

