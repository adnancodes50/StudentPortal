<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('packages') || !Schema::hasTable('categories')) {
            return;
        }

        $faker = Faker::create();
        $categoryIds = DB::table('categories')->pluck('id')->all();
        if (!$categoryIds) {
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            DB::table('packages')->insert([
                'name' => 'Package ' . $faker->words(3, true),
                'category_id' => $faker->randomElement($categoryIds),
                'description' => $faker->paragraph(),
                'duration_days' => $faker->numberBetween(3, 15),
                'total_price' => $faker->randomFloat(2, 300, 8000),
                'status' => $faker->randomElement(['active', 'inactive']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

