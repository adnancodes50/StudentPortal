<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'room_name')) {
                $table->string('room_name', 100)->nullable()->after('hotel_id');
            }

            if (!Schema::hasColumn('rooms', 'bed_capacity')) {
                $table->unsignedTinyInteger('bed_capacity')->default(1)->after('room_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'bed_capacity')) {
                $table->dropColumn('bed_capacity');
            }

            if (Schema::hasColumn('rooms', 'room_name')) {
                $table->dropColumn('room_name');
            }
        });
    }
};

