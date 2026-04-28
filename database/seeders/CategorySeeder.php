<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        $faker = Faker::create();
        $types = ['flight', 'umrah', 'tour'];

        for ($i = 1; $i <= 20; $i++) {
            DB::table('categories')->updateOrInsert(
                ['name' => "Category {$i}"],
                [
                    'type' => $types[array_rand($types)],
                    'description' => $faker->sentence(10),
                    'status' => $faker->randomElement(['active', 'inactive']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

