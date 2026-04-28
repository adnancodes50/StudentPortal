<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        foreach ([
            'wallet_transactions',
            'umrah_bookings',
            'umrah_packages',
            'ledger',
            'payments',
            'booking_packages',
            'booking_hotels',
            'booking_flights',
            'booking_passengers',
            'bookings',
            'package_details',
            'packages',
            'flight_prices',
            'flights',
            'hotel_prices',
            'rooms',
            'hotels',
            'banks',
            'categories',
            'users',
        ] as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->delete();
            }
        }
        Schema::enableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BankSeeder::class,
            HotelSeeder::class,
            RoomSeeder::class,
            HotelPriceSeeder::class,
            FlightSeeder::class,
            FlightPriceSeeder::class,
            PackageSeeder::class,
            PackageDetailsSeeder::class,
            BookingSeeder::class,
            BookingRelationsSeeder::class,
            PaymentSeeder::class,
            LedgerSeeder::class,
            UmrahSeeder::class,
            WalletTransactionsSeeder::class,
        ]);
    }
}
