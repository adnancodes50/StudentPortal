<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('rooms') || !Schema::hasTable('hotels')) {
            return;
        }

        $faker = Faker::create();
        $hotelIds = DB::table('hotels')->pluck('id')->all();
        if (!$hotelIds) {
            return;
        }

        $types = ['single', 'double', 'triple', 'quad', 'suite'];
        foreach ($hotelIds as $hotelId) {
            for ($i = 0; $i < 2; $i++) {
                DB::table('rooms')->insert([
                    'hotel_id' => $hotelId,
                    'room_type' => $faker->randomElement($types),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

