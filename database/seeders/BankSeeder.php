<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('banks')) {
            return;
        }

        $faker = Faker::create();
        $names = ['Habib Bank Limited', 'United Bank Limited', 'MCB Bank'];

        foreach ($names as $name) {
            $row = ['bank_name' => $name, 'created_at' => now(), 'updated_at' => now()];
            if (Schema::hasColumn('banks', 'account_title')) {
                $row['account_title'] = $faker->name();
            }
            if (Schema::hasColumn('banks', 'account_number')) {
                $row['account_number'] = $faker->bankAccountNumber();
            }
            DB::table('banks')->updateOrInsert(['bank_name' => $name], $row);
        }

        for ($i = 0; $i < 17; $i++) {
            $name = $faker->company() . ' Bank';
            $row = ['bank_name' => $name, 'created_at' => now(), 'updated_at' => now()];
            if (Schema::hasColumn('banks', 'account_title')) {
                $row['account_title'] = $faker->name();
            }
            if (Schema::hasColumn('banks', 'account_number')) {
                $row['account_number'] = $faker->bankAccountNumber();
            }
            DB::table('banks')->insert($row);
        }
    }
}

