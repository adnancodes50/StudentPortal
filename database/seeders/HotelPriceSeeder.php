<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HotelPriceSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('hotel_prices') || !Schema::hasTable('rooms')) {
            return;
        }

        $faker = Faker::create();
        $rooms = DB::table('rooms')->select(['id', 'hotel_id'])->get();
        if ($rooms->isEmpty()) {
            return;
        }

        foreach ($rooms as $room) {
            DB::table('hotel_prices')->insert([
                'hotel_id' => $room->hotel_id,
                'room_id' => $room->id,
                'price' => $faker->randomFloat(2, 50, 500),
                'valid_from' => now()->subDays($faker->numberBetween(1, 30))->toDateString(),
                'valid_to' => now()->addDays($faker->numberBetween(10, 90))->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

