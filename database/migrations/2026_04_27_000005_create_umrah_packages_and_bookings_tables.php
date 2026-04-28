<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('umrah_packages')) {
            Schema::create('umrah_packages', function (Blueprint $table) {
                $table->id();
                $table->string('package_name');
                $table->unsignedInteger('total_days');
                $table->decimal('price_per_person', 12, 2);
                $table->string('makkah_hotel')->nullable();
                $table->string('madinah_hotel')->nullable();
                $table->unsignedInteger('group_size')->nullable();
                $table->date('departure_date')->nullable();
                $table->date('return_date')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('umrah_bookings')) {
            Schema::create('umrah_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
                $table->foreignId('package_id')->constrained('umrah_packages')->onDelete('cascade');
                $table->timestamps();
                $table->unique('booking_id');
                $table->index('package_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('umrah_bookings');
        Schema::dropIfExists('umrah_packages');
    }
};

