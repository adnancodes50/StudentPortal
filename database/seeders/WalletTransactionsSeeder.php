<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WalletTransactionsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('wallet_transactions') || !Schema::hasColumn('users', 'role')) {
            return;
        }

        $faker = Faker::create();
        $agentIds = DB::table('users')->where('role', 'agent')->pluck('id')->all();
        if (!$agentIds) {
            return;
        }

        foreach (array_slice($agentIds, 0, 20) as $agentId) {
            $balance = 0;
            $txCount = 2;
            for ($i = 0; $i < $txCount; $i++) {
                $type = $faker->randomElement(['credit', 'debit']);
                $amount = $faker->randomFloat(2, 50, 2000);
                if ($type === 'debit' && $balance - $amount < 0) {
                    $type = 'credit';
                }
                $balance = $type === 'credit' ? ($balance + $amount) : ($balance - $amount);
                DB::table('wallet_transactions')->insert([
                    'agent_id' => $agentId,
                    'type' => $type,
                    'amount' => $amount,
                    'balance_after' => $balance,
                    'reference_type' => $faker->randomElement(['booking', 'payment', 'manual']),
                    'reference_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

