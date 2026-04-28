<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            if (!Schema::hasColumn('flights', 'origin_airport_code')) {
                $table->string('origin_airport_code', 10)->nullable()->after('departure_city');
            }

            if (!Schema::hasColumn('flights', 'destination_airport_code')) {
                $table->string('destination_airport_code', 10)->nullable()->after('arrival_city');
            }

            if (!Schema::hasColumn('flights', 'duration_minutes')) {
                $table->unsignedInteger('duration_minutes')->nullable()->after('arrival_time');
            }

            if (!Schema::hasColumn('flights', 'status')) {
                $table->enum('status', ['scheduled', 'delayed', 'cancelled'])->default('scheduled')->after('baggage_kg');
            }
        });

        Schema::table('flight_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('flight_prices', 'currency')) {
                $table->string('currency', 10)->default('PKR')->after('price');
            }

            if (!Schema::hasColumn('flight_prices', 'is_refundable')) {
                $table->boolean('is_refundable')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('flight_prices', function (Blueprint $table) {
            if (Schema::hasColumn('flight_prices', 'is_refundable')) {
                $table->dropColumn('is_refundable');
            }
            if (Schema::hasColumn('flight_prices', 'currency')) {
                $table->dropColumn('currency');
            }
        });

        Schema::table('flights', function (Blueprint $table) {
            if (Schema::hasColumn('flights', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('flights', 'duration_minutes')) {
                $table->dropColumn('duration_minutes');
            }
            if (Schema::hasColumn('flights', 'destination_airport_code')) {
                $table->dropColumn('destination_airport_code');
            }
            if (Schema::hasColumn('flights', 'origin_airport_code')) {
                $table->dropColumn('origin_airport_code');
            }
        });
    }
};

