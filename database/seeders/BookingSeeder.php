<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('bookings') || !Schema::hasTable('users') || !Schema::hasTable('categories')) {
            return;
        }

        $faker = Faker::create();
        $userIds = DB::table('users')->pluck('id')->all();
        $agentIds = Schema::hasColumn('users', 'role') ? DB::table('users')->where('role', 'agent')->pluck('id')->all() : [];
        $categoryIds = DB::table('categories')->pluck('id')->all();
        if (!$userIds || !$categoryIds) {
            return;
        }

        $types = $this->getBookingTypes();
        if (!$types) {
            $types = ['flight', 'umrah', 'package'];
        }

        for ($i = 0; $i < 120; $i++) {
            $row = [
                'user_id' => $faker->randomElement($userIds),
                'category_id' => $faker->randomElement($categoryIds),
                'booking_no' => 'BKG-' . strtoupper(Str::random(10)),
                'booking_type' => $faker->randomElement($types),
                'total_amount' => $faker->randomFloat(2, 100, 15000),
                'status' => $faker->randomElement(['draft', 'confirmed', 'cancelled']),
                'created_at' => now()->subDays($faker->numberBetween(0, 90)),
                'updated_at' => now(),
            ];
            if (Schema::hasColumn('bookings', 'reference_no')) {
                $row['reference_no'] = $row['booking_no'];
            }
            if (Schema::hasColumn('bookings', 'notes')) {
                $row['notes'] = $faker->boolean(40) ? $faker->sentence(12) : null;
            }
            if (Schema::hasColumn('bookings', 'agent_id')) {
                $row['agent_id'] = $agentIds ? $faker->randomElement($agentIds) : null;
            }
            DB::table('bookings')->insert($row);
        }
    }

    private function getBookingTypes(): array
    {
        if (!Schema::hasColumn('bookings', 'booking_type') || DB::getDriverName() !== 'mysql') {
            return ['flight', 'umrah', 'package'];
        }
        $col = DB::select("SHOW COLUMNS FROM `bookings` LIKE 'booking_type'");
        if (!$col) {
            return ['flight', 'umrah', 'package'];
        }
        $type = $col[0]->Type ?? '';
        if (!is_string($type) || !str_starts_with($type, 'enum(')) {
            return ['flight', 'umrah', 'package'];
        }
        $inside = trim(substr($type, 5), ')');
        return array_values(array_filter(array_map(fn ($v) => trim($v, " '"), explode(',', $inside))));
    }
}

