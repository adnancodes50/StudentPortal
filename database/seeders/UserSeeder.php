<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = '123456';
        $faker = Faker::create();

        $this->upsertUser('admin@example.com', [
            'name' => 'Admin User',
            'password' => Hash::make($password),
            'type' => 'admin',
            'role' => 'admin',
            'status' => 'active',
            'phone' => '0300-0000000',
            'passport_no' => 'A00000001',
            'address' => 'Admin Address',
        ]);

        $agentsCount = 20;
        for ($i = 1; $i <= $agentsCount; $i++) {
            $this->upsertUser("agent{$i}@example.com", [
                'name' => $faker->name(),
                'password' => Hash::make($password),
                'type' => 'user',
                'role' => 'agent',
                'status' => $faker->randomElement(['active', 'inactive']),
                'phone' => $faker->numerify('03##-#######'),
                'passport_no' => strtoupper($faker->bothify('A#######')),
                'address' => $faker->address(),
            ]);
        }

        $usersCount = 40;
        for ($i = 1; $i <= $usersCount; $i++) {
            $this->upsertUser("user{$i}@example.com", [
                'name' => $faker->name(),
                'password' => Hash::make($password),
                'type' => 'user',
                'role' => 'user',
                'status' => $faker->randomElement(['active', 'inactive']),
                'phone' => $faker->numerify('03##-#######'),
                'passport_no' => strtoupper($faker->bothify('B#######')),
                'address' => $faker->address(),
            ]);
        }
    }

    private function upsertUser(string $email, array $data): void
    {
        $allowed = [];
        foreach ($data as $key => $value) {
            if (Schema::hasColumn('users', $key)) {
                $allowed[$key] = $value;
            }
        }
        $allowed['email'] = $email;
        User::updateOrCreate(['email' => $email], $allowed);
    }
}
