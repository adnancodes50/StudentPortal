<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('payments') || !Schema::hasTable('bookings')) {
            return;
        }

        $faker = Faker::create();
        $bookingIds = DB::table('bookings')->pluck('id')->all();
        if (!$bookingIds) {
            return;
        }

        $agentIds = (Schema::hasColumn('payments', 'agent_id') && Schema::hasColumn('users', 'role'))
            ? DB::table('users')->where('role', 'agent')->pluck('id')->all()
            : [];

        for ($i = 0; $i < 60; $i++) {
            $row = [
                'booking_id' => $faker->randomElement($bookingIds),
                'amount' => $faker->randomFloat(2, 50, 15000),
                'status' => $faker->randomElement(['pending', 'completed']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('payments', 'method')) {
                $row['method'] = $faker->randomElement(['cash', 'bank_transfer', 'card']);
            }
            if (Schema::hasColumn('payments', 'transaction_id')) {
                $row['transaction_id'] = strtoupper($faker->bothify('TXN########'));
            }
            if (Schema::hasColumn('payments', 'proof_image')) {
                $row['proof_image'] = null;
            }
            if (Schema::hasColumn('payments', 'agent_id')) {
                $row['agent_id'] = $agentIds ? $faker->randomElement($agentIds) : null;
            }
            DB::table('payments')->insert($row);
        }
    }
}

