<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LedgerSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('ledger') || !Schema::hasTable('bookings')) {
            return;
        }

        $faker = Faker::create();
        $bookingIds = DB::table('bookings')->pluck('id')->all();
        if (!$bookingIds) {
            return;
        }

        foreach (array_slice($bookingIds, 0, 60) as $bookingId) {
            $credit = $faker->boolean(60) ? $faker->randomFloat(2, 100, 8000) : 0;
            $debit = $credit > 0 ? 0 : $faker->randomFloat(2, 50, 3000);
            DB::table('ledger')->insert([
                'booking_id' => $bookingId,
                'debit' => $debit,
                'credit' => $credit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

